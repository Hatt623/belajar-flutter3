<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Order;
use App\Models\Tiket;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       $this->call([
            UsersSeeder::class,  
            EventsSeeder::class,
            OrdersSeeder::class,
            TiketsSeeder::class                  
        ]);
    }
}
