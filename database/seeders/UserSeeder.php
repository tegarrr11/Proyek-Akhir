<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'bem', 'mahasiswa', 'dosen'];

        foreach ($roles as $role) {
            User::updateOrCreate(
                ['email' => "$role@example.com"],
                [
                    'name' => Str::ucfirst($role),
                    'password' => Hash::make('password'),
                    'role' => $role,
                    'email_verified_at' => Carbon::now(),
                ]
            );
        }
    }
}
