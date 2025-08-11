<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use DB;
use App\Models\Tiket;

class TiketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tikets')->delete();

        Tiket::create([
            'user_id' => 1,
            'order_id' => 1,
            'event_id' => 1,
            'name' => 'AFKG Jakarta Tour',
            'location' => 'Jakarta, Indonesia',
            'code' => 'ORD-ZPUA2P8T',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
        ]);
    }
}
