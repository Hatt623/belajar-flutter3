<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Import
use DB;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('rahasia'),
            'Admin' => 1,
        ]);

        User::create([
            'name' => 'Member',
            'email' => 'member@gmail.com',
            'password' => bcrypt('rahasia'),
            'Admin' => 0,
        ]);
    }
}
