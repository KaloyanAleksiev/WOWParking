<?php

namespace Database\Seeders;

use App\Models\Rate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('rates')->truncate();
        Schema::enableForeignKeyConstraints();

        $date = Carbon::now();

        Rate::insert([
            [
                'name' => 'Daily',
                'from' => '08:00',
                'to' => '18:00',
                'amount_per_hour' => 3,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'name' => 'Nightly',
                'from' => '18:00',
                'to' => '08:00',
                'amount_per_hour' => 2,
                'created_at' => $date,
                'updated_at' => $date
            ],
        ]);
    }
}
