<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CreditController extends Controller
{
    protected array $creditPacks = [
        ['credits' => 50,   'price_inr' => 99,   'price_id' => 'price_50credits'],
        ['credits' => 200,  'price_inr' => 299,  'price_id' => 'price_200credits'],
        ['credits' => 1000, 'price_inr' => 999,  'price_id' => 'price_1000credits'],
    ];

    public function getPlans()
    {
        return response()->json(['plans' => $this->creditPacks]);
    }

    public function createPaymentIntent(Request $request)
    {
        $request->validate(['credits' => 'required|integer|in:50,200,1000']);

        $pack = collect($this->creditPacks)->firstWhere('credits', $request->credits);
        if (!$pack) return response()->json(['error' => 'Invalid plan'], 400);

        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount'   => $pack['price_inr'] * 100, // paise
            'currency' => 'inr',
            'metadata' => [
                'user_id' => $request->user()->id,
                'credits' => $request->credits,
            ],
        ]);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'amount'        => $pack['price_inr'],
            'credits'       => $pack['credits'],
        ]);
    }

    public function handleWebhook(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $signature, config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent  = $event->data->object;
            $userId  = $intent->metadata->user_id;
            $credits = (int) $intent->metadata->credits;

            $user = \App\Models\User::find($userId);
            if ($user) {
                $user->increment('credits', $credits);
                CreditTransaction::create([
                    'user_id'                  => $userId,
                    'type'                     => 'purchase',
                    'amount'                   => $credits,
                    'balance_after'            => $user->credits,
                    'stripe_payment_intent_id' => $intent->id,
                    'description'              => "Purchased {$credits} credits",
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
