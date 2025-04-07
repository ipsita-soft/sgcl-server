<?php

namespace Database\Seeders;

use App\Models\ExternalClientAccessToken;
use Illuminate\Database\Seeder;

class ExternalClientAccessTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Client A',
                'clientId' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
                'clientSecret' => 'e10adc3949ba59abbe56e057f20f883e',
            ],
            [
                'name' => 'BIDA',
                'clientId' => 'c56a4180-65aa-42ec-a945-5fd21dec0538',
                'clientSecret' => '25d55ad283aa400af464c76d713c07ad',
            ],
        ];

        foreach ($data as $item) {
            $client = new ExternalClientAccessToken();
            $client->name = $item['name'];
            $client->clientId = $item['clientId'];
            $client->clientSecret = $item['clientSecret'];
            $client->api_token = null;
            $client->save();
        }
    }
}
