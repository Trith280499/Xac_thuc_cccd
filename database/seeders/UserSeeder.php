<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $userData = [
            [
                'mssv' => '48.01.104.030',
                'so_cccd' => '079204021537',
            ],
            [
                'mssv' => '20240002',
                'so_cccd' => '001123456788',
            ],
            [
                'mssv' => '20240003',
                'so_cccd' => '001123456787',
            ],
            [
                'mssv' => '20240004',
                'so_cccd' => '001123456786',
            ],
            [
                'mssv' => '20240005',
                'so_cccd' => '001123456785',
            ]
        ];

        foreach ($userData as $data) {
            User::create($data);
        }
    }
}