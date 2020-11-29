<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('vehicle_types')->truncate();
        Schema::enableForeignKeyConstraints();

        $date = Carbon::now();

        VehicleType::insert([
            [
                'name' => 'Car',
                'slots' => 1,
                'created_at' => $date,
                'updated_at' => $date
            ],
        ]);
    }
}
