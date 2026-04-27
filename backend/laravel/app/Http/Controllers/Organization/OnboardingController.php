<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    /**
     * Step 1: Register organization
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'domain' => 'required|string|max:255',
        ]);

        $org = Organization::create([
            'name'   => $request->name,
            'domain' => strtolower($request->domain),
        ]);

        // Add requesting user as owner
        $org->members()->attach($request->user()->id, ['role' => 'owner']);

        return response()->json([
            'organization' => $org,
            'next_step'    => 'domain_verification',
            'message'      => 'Organization created. Next: verify domain ownership.',
        ]);
    }

    /**
     * Step 2: Get domain verification options
     */
    public function getVerificationOptions(Request $request, $orgId)
    {
        $org = $request->user()->organizations()->findOrFail($orgId);
        $token = 'mds-verify-' . Str::random(32);

        // Cache token for 24h
        cache()->put("domain_verify_{$orgId}", $token, now()->addHours(24));

        return response()->json([
            'domain' => $org->domain,
            'options' => [
                'dns_txt' => [
                    'type'  => 'DNS TXT Record',
                    'name'  => "_mds-verify.{$org->domain}",
                    'value' => $token,
                    'instructions' => 'Add this TXT record in your DNS provider. Takes 5-60 mins to propagate.',
                ],
                'http_file' => [
                    'type'  => 'HTTP File Upload',
                    'url'   => "https://{$org->domain}/.well-known/mds-verify.txt",
                    'value' => $token,
                    'instructions' => 'Create this file with the token as content. Instant verification.',
                ],
            ],
        ]);
    }

    /**
     * Step 3: Verify domain ownership
     */
    public function verifyDomain(Request $request, $orgId)
    {
        $org = $request->user()->organizations()->findOrFail($orgId);
        $method = $request->input('method', 'dns_txt');
        $token = cache()->get("domain_verify_{$orgId}");

        if (!$token) {
            return response()->json(['error' => 'Verification token expired. Please regenerate.'], 400);
        }

        $verified = match($method) {
            'dns_txt'   => $this->verifyDnsTxt($org->domain, $token),
            'http_file' => $this->verifyHttpFile($org->domain, $token),
            default     => false,
        };

        if (!$verified) {
            return response()->json([
                'verified' => false,
                'message'  => 'Verification failed. DNS records can take up to 60 minutes to propagate.',
            ], 422);
        }

        $org->update([
            'domain_verified'    => true,
            'domain_verified_at' => now(),
            'verification_method' => $method,
        ]);

        return response()->json([
            'verified'     => true,
            'organization' => $org,
            'message'      => 'Domain verified! Your organization is now active.',
        ]);
    }

    protected function verifyDnsTxt(string $domain, string $token): bool
    {
        $records = @dns_get_record("_mds-verify.{$domain}", DNS_TXT);
        if (!$records) return false;
        foreach ($records as $record) {
            if (isset($record['txt']) && $record['txt'] === $token) return true;
            if (isset($record['entries']) && in_array($token, $record['entries'])) return true;
        }
        return false;
    }

    protected function verifyHttpFile(string $domain, string $token): bool
    {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->get("https://{$domain}/.well-known/mds-verify.txt");
            return $response->ok() && trim($response->body()) === $token;
        } catch (\Exception $e) {
            return false;
        }
    }
}
