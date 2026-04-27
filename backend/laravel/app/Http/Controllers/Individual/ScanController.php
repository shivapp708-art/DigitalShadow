<?php

namespace App\Http\Controllers\Individual;

use App\Http\Controllers\Controller;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScanController extends Controller
{
    protected string $osintUrl;

    public function __construct()
    {
        $this->osintUrl = config('services.osint.url', env('FASTAPI_URL', 'http://fastapi:8000'));
    }

    /**
     * Run a breach check on email
     */
    public function breachEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = $request->user();

        if (!$user->hasCredits(1)) {
            return response()->json(['error' => 'Insufficient credits. Please top up.'], 402);
        }

        $scan = Scan::create([
            'user_id'      => $user->id,
            'scan_type'    => 'breach_email',
            'status'       => 'running',
            'target'       => $request->email,
            'credits_used' => 1,
        ]);

        try {
            $response = Http::timeout(30)->post("{$this->osintUrl}/individuals/breach/email", [
                'email' => $request->email,
            ]);

            $results = $response->json();

            $scan->update([
                'status'       => 'completed',
                'results'      => $results,
                'risk_score'   => $this->calculateRiskScore($results),
                'ai_summary'   => $results['ai_summary'] ?? null,
                'completed_at' => now(),
            ]);

            $user->deductCredits(1);

        } catch (\Exception $e) {
            $scan->update(['status' => 'failed']);
            return response()->json(['error' => 'Scan failed. Credits not deducted.'], 500);
        }

        return response()->json([
            'scan_id' => $scan->id,
            'results' => $scan->results,
            'risk_score' => $scan->risk_score,
        ]);
    }

    /**
     * Run username enumeration
     */
    public function usernameEnum(Request $request)
    {
        $request->validate(['username' => 'required|string|max:100|alpha_dash']);
        $user = $request->user();

        if (!$user->hasCredits(1)) {
            return response()->json(['error' => 'Insufficient credits.'], 402);
        }

        $scan = Scan::create([
            'user_id'      => $user->id,
            'scan_type'    => 'username_enum',
            'status'       => 'running',
            'target'       => $request->username,
            'credits_used' => 1,
        ]);

        $response = Http::timeout(30)->post("{$this->osintUrl}/individuals/username/enum", [
            'username' => $request->username,
        ]);

        $results = $response->json();

        $scan->update([
            'status'       => 'completed',
            'results'      => $results,
            'completed_at' => now(),
        ]);

        $user->deductCredits(1);

        return response()->json(['scan_id' => $scan->id, 'results' => $results]);
    }

    /**
     * Get scan history
     */
    public function history(Request $request)
    {
        $scans = $request->user()->scans()
            ->select(['id', 'scan_type', 'status', 'target', 'risk_score', 'credits_used', 'created_at'])
            ->latest()
            ->paginate(20);

        return response()->json($scans);
    }

    protected function calculateRiskScore(array $results): int
    {
        $score = 0;
        $level = $results['risk_level'] ?? 'low';
        $map   = ['low' => 0, 'medium' => 30, 'high' => 60, 'critical' => 90];
        return $map[$level] ?? 0;
    }
}
