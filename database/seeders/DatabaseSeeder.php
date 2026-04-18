<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminLevel = \App\Models\Level::firstOrCreate([
            'level_kode' => 'ADM',
        ], [
            'level_nama' => 'Admin',
        ]);

        \App\Models\User::firstOrCreate([
            'username' => 'admin',
        ], [
            'level_id' => $adminLevel->level_id,
            'email' => 'admin@gmail.com',
            'nama' => 'Administrator',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
}
