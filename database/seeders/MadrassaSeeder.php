<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Madrassa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MadrassaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $location = Location::where('id', '1')->first();

        Madrassa::create([
            'name' => 'Madrasa Al munawar',
            'location_id' => $location->id,
        ]);
    }
}
