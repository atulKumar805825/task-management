<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create ONE admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => md5('admin123'),
            'role' => 'admin',
        ]);

        // Create 49 normal users
        User::factory()->count(49)->create();
    }
}
