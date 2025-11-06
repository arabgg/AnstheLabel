<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_user')->insert([
            [
                'nama' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('Super123!'),
                'role' => 'super_admin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'nama' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('Biasa123!'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
        ]);
    }
}
