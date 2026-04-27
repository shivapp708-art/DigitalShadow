<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptOutLinkSeeder extends Seeder
{
    public function run(): void
    {
        $brokers = [
            ['name' => 'Truecaller', 'opt_out_url' => 'https://www.truecaller.com/unlisting', 'country' => 'IN', 'difficulty' => 'easy'],
            ['name' => 'Spokeo', 'opt_out_url' => 'https://www.spokeo.com/optout', 'country' => 'US', 'difficulty' => 'medium'],
            ['name' => 'WhitePages', 'opt_out_url' => 'https://www.whitepages.com/suppression_requests', 'country' => 'US', 'difficulty' => 'medium'],
            ['name' => 'BeenVerified', 'opt_out_url' => 'https://www.beenverified.com/app/optout/search', 'country' => 'US', 'difficulty' => 'easy'],
            ['name' => 'Intelius', 'opt_out_url' => 'https://www.intelius.com/optout', 'country' => 'US', 'difficulty' => 'hard'],
            ['name' => 'JustDial', 'opt_out_url' => 'https://www.justdial.com/privacy', 'country' => 'IN', 'difficulty' => 'hard'],
        ];

        foreach ($brokers as $broker) {
            DB::table('data_broker_opt_outs')->updateOrInsert(
                ['name' => $broker['name']],
                array_merge($broker, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
