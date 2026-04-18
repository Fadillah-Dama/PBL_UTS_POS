<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminLevel = Level::firstOrCreate([
            'level_kode' => 'ADM',
        ], [
            'level_nama' => 'Admin',
        ]);

        Level::firstOrCreate([
            'level_kode' => 'KSR',
        ], [
            'level_nama' => 'Kasir',
        ]);

        User::firstOrCreate([
            'username' => 'admin',
        ], [
            'level_id' => $adminLevel->level_id,
            'email' => 'admin@gmail.com',
            'nama' => 'Administrator',
            'password' => Hash::make('password'),
        ]);
    }
}
