<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemediationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'category' => 'breach',
                'title' => 'Password Breach Found',
                'severity' => 'high',
                'steps' => json_encode([
                    'Change your password immediately on the breached service',
                    'Change the same password on any other sites where you used it',
                    'Enable two-factor authentication (2FA) on all accounts',
                    'Check for unauthorized activity in your account',
                    'Consider using a password manager like Bitwarden or 1Password',
                ]),
                'estimated_time_minutes' => 30,
            ],
            [
                'category' => 'data_broker',
                'title' => 'Personal Data Listed on Data Broker',
                'severity' => 'medium',
                'steps' => json_encode([
                    'Visit the data broker website directly',
                    'Find their opt-out or removal page (usually in footer)',
                    'Submit removal request with your details',
                    'Wait 30-45 days for processing',
                    'Verify removal after 45 days',
                ]),
                'estimated_time_minutes' => 15,
            ],
            [
                'category' => 'social_exposure',
                'title' => 'Personal Info Exposed on Social Media',
                'severity' => 'low',
                'steps' => json_encode([
                    'Review your privacy settings on each platform',
                    'Remove or restrict access to phone number and email',
                    'Disable public visibility of friend/follower lists',
                    'Remove personal address and workplace if not necessary',
                    'Enable login alerts for all platforms',
                ]),
                'estimated_time_minutes' => 20,
            ],
        ];

        foreach ($templates as $template) {
            DB::table('remediation_templates')->updateOrInsert(
                ['category' => $template['category'], 'title' => $template['title']],
                array_merge($template, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
