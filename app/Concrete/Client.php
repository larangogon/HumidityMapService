<?php

namespace App\Concrete;

use App\Contracts\ClientContract;
use GuzzleHttp\Client as ClientBase;

class Client extends ClientBase implements ClientContract
{
}
