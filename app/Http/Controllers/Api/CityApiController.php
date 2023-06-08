<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ConfigurationContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetHumidityRequest;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CityApiController extends Controller
{
    protected ConfigurationContract $repository;

    public function __construct(ConfigurationContract $repository)
    {
        $this->repository = $repository;
    }

    public function index(): JsonResponse
    {
        $cities = City::all();

        return response()->json($cities);
    }

    public function getHumidity(GetHumidityRequest $request): JsonResponse
    {
        $humidity = $this->repository->getHumidity($request);

        return response()->json(['humidity' => $humidity]);
    }
}
