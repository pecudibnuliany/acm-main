<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = ['writer', 'publisher', 'ceo', 'administrator'];
        $default = [
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10)
        ];

        foreach ($users as $value) {
            User::create([...$default, ...[
                'name' => $value,
                'email' => $value . '@gmail.com',
            ]])->assignRole($value);
        }
    }
}
