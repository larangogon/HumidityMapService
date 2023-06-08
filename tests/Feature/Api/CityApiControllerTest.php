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
     * @dataProvider dataProvider
     */
    public function testGetHumidity($humidity): void
    {
        $city = City::factory()->create();

        $responseBody = json_encode(['current' => ['humidity' => $humidity]]);

        $mockResponse = new Response(200, [], $responseBody);
        $mockClient = new MockHandler([$mockResponse]);

        $handlerStack = HandlerStack::create($mockClient);

        App::bind(ClientContract::class, static function () use ($handlerStack) {
            return  new Client(['handler' => $handlerStack]);
        });

        $response = $this->postJson(route('cities.getHumidity'), ['cityId' => $city->id]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertArrayHasKey('humidity', $response->getData(true));
        $this->assertDatabaseHas('histories', [
            'city_id' => $city->id,
            'humidity' => $humidity,
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
                'id' => $city->id,
                'name' => $city->name,
                'lat' => $city->lat,
                'lon' => $city->lon,
                'created_at' => $city->created_at,
                'updated_at' => $city->updated_at,
            ],
        ]);
    }

    public static function dataProvider(): array
    {
        return [
            'humidity int' => [80],
            'humidity float' => [87.5]
        ];
    }
}
