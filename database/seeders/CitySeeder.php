<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $filePath = base_path('resources/docs/cities.json');
        $data = collect(json_decode(file_get_contents($filePath), true));

        foreach ($data as $country) {
            City::factory()->create([
                'name' => $country['name'],
                'lat' => $country['lat'],
                'lon' => $country['lon'],
            ]);
        }
    }
}
