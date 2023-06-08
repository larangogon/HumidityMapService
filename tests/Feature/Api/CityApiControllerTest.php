<?php

namespace Tests\Feature\Api;

use App\Contracts\ClientContract;
use App\Http\Controllers\Api\CityApiController;
use App\Http\Requests\GetHumidityRequest;
use App\Models\City;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;

class CityApiControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function testGetHumidity()
    {
        $city = City::factory()->create();

        $responseBody = json_encode(['current' => ['humidity' => 80]]);

        $mockResponse = new Response(200, [], $responseBody);
        $mockClient = new MockHandler([$mockResponse]);

        $handlerStack = HandlerStack::create($mockClient);

        App::bind(ClientContract::class, static function () use ($handlerStack) {
            return  new Client(['handler' => $handlerStack]);
        });

        $controller = new CityApiController();
        $request = $this->createMock(GetHumidityRequest::class);
        $request->expects($this->once())
            ->method('input')
            ->with('cityId')
            ->willReturn($city->id);

        $response = $controller->getHumidity($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertArrayHasKey('humidity', $response->getData(true));
        $this->assertSame(80, $response->getData(true)['humidity']);
    }
}
