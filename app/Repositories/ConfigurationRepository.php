<?php

namespace App\Repositories;

use App\Contracts\ClientContract;
use App\Contracts\ConfigurationContract;
use App\Exceptions\HumidityMapServiceException;
use App\Http\Requests\GetHumidityRequest;
use App\Models\City;
use App\Models\History;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ConfigurationRepository implements ConfigurationContract
{
    /**
     * @throws GuzzleException|HumidityMapServiceException
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
     * @throws GuzzleException|HumidityMapServiceException
     */
    private function getHumidityFromExternalAPI(City $city)
    {
        try {
            $response = $this->getClient()->get('https://api.openweathermap.org/data/3.0/onecall', [
                'query' => [
                    'lat' => $city->lat,
                    'lon' => $city->lon,
                    'appid' => Config::get('app.appid_open_weather'),
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return data_get($data, 'current.humidity', 'no se encontro humedad');
        } catch (ClientException $e) {
            Log::info(Str::uuid(), ['mensage' => $e->getMessage()]);
            throw new HumidityMapServiceException('Ocurrió un error de cliente.');
        }
    }
}
