<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Untuk menghapus data jika diperlukan

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opsional: Kosongkan tabel user dan role_user jika menjalankan ulang seeder
        // DB::table('role_user')->delete();
        // DB::table('users')->delete();

        $adminRole = Role::where('slug', 'admin')->first();
        $userRole = Role::where('slug', 'user')->first();

        if (!$adminRole || !$userRole) {
            $this->command->error('Roles admin or user not found. Please run RoleSeeder first.');
            return;
        }

        // Buat Admin User
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'email_verified_at' => now(),
        ]);
        $adminUser->roles()->attach($adminRole);

        // Buat Standard User
        $standardUser = User::create([
            'name' => 'Standard User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'email_verified_at' => now(),
        ]);
        $standardUser->roles()->attach($userRole);

        // Anda bisa menggunakan factory untuk membuat lebih banyak user jika diperlukan
        // User::factory(5)->create()->each(function ($user) use ($userRole) {
        //     $user->roles()->attach($userRole);
        // });
    }
}