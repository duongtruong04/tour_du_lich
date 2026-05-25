<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'site_name' => 'Tour Travel',
            'site_title' => 'Trải nghiệm du lịch chuyên nghiệp',
            'contact_email' => 'contact@tour-travel.com',
            'contact_phone' => '1900 6789',
            'address' => '123 Đường Du Lịch, Quận 1, Tp. Hồ Chí Minh',
            'facebook_url' => 'https://facebook.com/travel',
            'twitter_url' => 'https://twitter.com/travel',
            'instagram_url' => 'https://instagram.com/travel',
            'logo_text' => 'TourTravel',
            'bank_name' => 'Vietcombank',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'CTY TNHH TOUR TRAVEL',
           'openrouter_api_key' => env('OPENROUTER_API_KEY', ''),
            'openrouter_model' => 'openai/gpt-oss-120b:free',
        ];

        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
