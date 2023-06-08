<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filePath = base_path('resources/docs/cities.json');
        $data = collect(json_decode(file_get_contents($filePath), true));

        $_data = [];

        foreach ($data as $country) {
            $_data[] = array_replace_recursive($country, [
                'name' => $country['name'],
                'lat' => $country['lat'],
                'lon' => $country['lon'],
            ]);
        }

        return $this->faker->unique()->randomElement($_data);
    }
}
