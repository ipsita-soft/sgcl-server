<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settingsData = [
            [
                'module' => 'General',
                'name' => 'Company Name',
                'data' => json_encode(['value' => 'Sundarban Gas Company Limited']),
            ],
            [
                'module' => 'PaymentGetaway',
                'name' => 'SPG',
                'data' => json_encode(['user_name' => 'a2i@pmo','password' => 'sbPayment0002','credit_account'=>'0002601020871','credit_amount'=>'200']),
            ],
            [

                'module' => 'Email',
                'name' => 'SMTP Configuration',
                'data' => json_encode(['host' => 'smtp.example.com', 'port' => '587', 'username' => 'user@example.com', 'password' => 'password']),
            ],
            [
                'module' => 'Notification',
                'name' => 'Default Notification Settings',
                'data' => json_encode(['email' => true, 'sms' => false, 'push' => true]),
            ],
        ];

        foreach ($settingsData as $data) {
            Settings::create($data);
        }
    }
}
