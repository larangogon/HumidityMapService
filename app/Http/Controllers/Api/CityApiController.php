<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ClientContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetHumidityRequest;
use App\Models\City;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

class CityApiController extends Controller
{
    public function index(): JsonResponse
    {
        $cities = City::all();

        return response()->json($cities);
    }

    /**
     * @throws GuzzleException
     */
    public function getHumidity(GetHumidityRequest $request): JsonResponse
    {
        $cityId = $request->input('cityId');
        $city = City::find($cityId);

        if (!$city) {
            return response()->json(['error' => 'Ciudad no encontrada'], 404);
        }

        $humidity = $this->getHumidityFromExternalAPI($city);

        return response()->json(['humidity' => $humidity]);
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
