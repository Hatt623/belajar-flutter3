<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use DB;
use App\Models\Event;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->delete();

        Event::create([
            'image' => 'AFKGTOUR.png',
            'name' => 'AFKG Jakarta Tour',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
            'location' => 'Jakarta, Indonesia',
            'description' => 'Join our part of Asia tour in Jakarta'
        ]);
    }
}
