<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use App\Models\Statistic;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin JapranMCEH',
            'email' => 'admin@japranmceh.com',
            'password' => bcrypt('admin123'),
        ]);

        Setting::create([
            'website_name' => 'JapranMCEH',
            'hero_title' => 'Premium Minecraft Store',
            'hero_subtitle' => 'Top up Minecraft, Minecoins, Gift Card & Jasa Pembuatan Skin dengan harga terbaik dan proses cepat.',
            'whatsapp_number' => '6281234567890',
        ]);

        Statistic::create([
            'total_customer' => 150,
            'total_transaction' => 320,
            'total_product' => 25,
        ]);
    }
}
