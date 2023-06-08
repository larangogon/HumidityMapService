<?php

namespace App\Repositories;

use App\Contracts\ClientContract;
use App\Contracts\ConfigurationContract;
use App\Http\Requests\GetHumidityRequest;
use App\Models\City;
use App\Models\History;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;

class ConfigurationRepository implements ConfigurationContract
{
    /**
     * @throws GuzzleException
     */
    public function getHumidity(GetHumidityRequest $request): string|int
    {
        $cityId = $request->input('cityId');

        /** @var City $city */
        $city = City::find($cityId);

        if (!$city) {
            return response()->json(['error' => 'Ciudad no encontrada'], 404);
        }

        $humidity = $this->getHumidityFromExternalAPI($city);

        History::create([
            'humidity' => $humidity,
            'city_id' => $city->id,
        ]);

        return $humidity;
    }

    /**
     * @return ClientContract
     */
    protected function getClient()
    {
        return app(ClientContract::class);
    }

    /**
     * @throws GuzzleException
     */
    private function getHumidityFromExternalAPI(City $city)
    {
        $response = $this->getClient()->get('https://api.openweathermap.org/data/3.0/onecall', [
            'query' => [
                'lat' => $city->lat,
                'lon' => $city->lon,
                'appid' => Config::get('app.appid_open_weather'),
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return data_get($data, 'current.humidity');
    }
}
