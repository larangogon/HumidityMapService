<?php

namespace App\Contracts;

use App\Http\Requests\GetHumidityRequest;

interface ConfigurationContract
{
    public function getHumidity(GetHumidityRequest $request): string|int;
}
