<?php

namespace Tests\Feature\Api;

use App\Contracts\ClientContract;
use App\Models\City;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class CityApiControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function testGetHumidity(): void
    {
        $city = City::factory()->create();

        $responseBody = json_encode(['current' => ['humidity' => 80]]);

        $mockResponse = new Response(200, [], $responseBody);
        $mockClient = new MockHandler([$mockResponse]);

        $handlerStack = HandlerStack::create($mockClient);

        App::bind(ClientContract::class, static function () use ($handlerStack) {
            return  new Client(['handler' => $handlerStack]);
        });

        $response = $this->postJson(route('cities.getHumidity'), ['cityId' => $city->id]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertArrayHasKey('humidity', $response->getData(true));
        $this->assertSame(80, $response->getData(true)['humidity']);

        $this->assertDatabaseHas('histories', [
            'city_id' => $city->id,
            'humidity' => 80,
        ]);
    }

    /**
     * @test
     */
    public function getHumidityError(): void
    {
        $city = City::factory()->create();

        Config::set('app.appid_open_weather', '');

        $this->postJson(route('cities.getHumidity'), ['cityId' => $city->id]);

        $this->assertDatabaseMissing('histories', [
            'city_id' => $city->id,
            'humidity' => 80,
        ]);
    }

    /**
     * @test
     */
    public function getCitiesAll(): void
    {
        $city = City::factory()->create([
            'name' => 'New York',
            'lat' => '40.71427',
            'lon' => '-74.00597',
        ]);

        $response = $this->getJson(route('cities'));
        $response->assertOk();
        $response->assertExactJson([
            [
                'id' => 1,
                'name' => $city->name,
                'lat' => $city->lat,
                'lon' => $city->lon,
                'created_at' => $city->created_at,
                'updated_at' => $city->updated_at,
            ],
        ]);
    }
}
