<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionTierSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'free',
                'display_name' => 'Free',
                'price_inr' => 0,
                'price_usd' => 0,
                'credits_per_month' => 10,
                'features' => json_encode([
                    'breach_check' => true,
                    'username_scan' => 3,
                    'name_scan' => 1,
                    'attack_surface' => false,
                    'ai_remediation' => false,
                    'export_pdf' => false,
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'individual_basic',
                'display_name' => 'Individual Basic',
                'price_inr' => 49900, // ₹499/month in paise
                'price_usd' => 599,   // $5.99/month in cents
                'credits_per_month' => 100,
                'features' => json_encode([
                    'breach_check' => true,
                    'username_scan' => 20,
                    'name_scan' => 10,
                    'attack_surface' => false,
                    'ai_remediation' => true,
                    'export_pdf' => true,
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'individual_pro',
                'display_name' => 'Individual Pro',
                'price_inr' => 149900, // ₹1499/month
                'price_usd' => 1499,
                'credits_per_month' => 500,
                'features' => json_encode([
                    'breach_check' => true,
                    'username_scan' => 'unlimited',
                    'name_scan' => 50,
                    'attack_surface' => false,
                    'ai_remediation' => true,
                    'export_pdf' => true,
                    'monitoring' => true,
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'org_starter',
                'display_name' => 'Organization Starter',
                'price_inr' => 499900, // ₹4999/month
                'price_usd' => 4999,
                'credits_per_month' => 2000,
                'features' => json_encode([
                    'attack_surface' => true,
                    'employee_monitoring' => 25,
                    'ai_remediation' => true,
                    'threat_intel' => true,
                    'ciso_dashboard' => true,
                    'export_pdf' => true,
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($tiers as $tier) {
            DB::table('subscription_tiers')->updateOrInsert(
                ['name' => $tier['name']],
                array_merge($tier, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
