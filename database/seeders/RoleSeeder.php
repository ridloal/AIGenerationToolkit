<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Tambahkan ini
use Illuminate\Support\Facades\DB; // Tambahkan ini

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel roles terlebih dahulu jika diperlukan
        // DB::table('roles')->delete(); // Uncomment jika ingin menghapus data lama setiap kali seed

        Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'User with full system access'
        ]);

        Role::create([
            'name' => 'Standard User',
            'slug' => 'user',
            'description' => 'User with basic access to tools'
        ]);

        // Tambahkan role lain jika perlu
        // Role::create([
        //     'name' => 'Editor',
        //     'slug' => 'editor',
        //     'description' => 'User who can edit content'
        // ]);
    }
}