<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'iamwhuy2004@gmail.com',
            'password' => Hash::make('11111111'), // Bạn nên thay đổi mật khẩu này
            'role' => 'admin', // Gán vai trò mặc định là 'admin'
        ]);
    }
}
