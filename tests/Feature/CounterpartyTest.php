<?php

use App\Models\Counterparty;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CounterpartyTest extends TestCase
{
    use DatabaseTransactions;

    public function testShouldResponseOkForCreateCounterparty()
    {
        Http::fake([
            'https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party' => Http::response([
                'suggestions' => [
                    [
                        'value' => 'ПАО СБЕРБАНК',
                        'data' => [
                            'inn' => '1234567892',
                            'name' => [
                                'short_with_opf' => 'ПАО СБЕРБАНК',
                            ],
                            'ogrn' => '1027700132195',
                            'address' => [
                                'unrestricted_value' => '117312, г Москва, ул Вавилова, д 19',
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $user = User::factory()->create(
            [
                'email' => 'test@example.com',
                'password' => 'password'
            ]
        );

        Sanctum::actingAs($user);

        $response = $this->postJson(
            route('counterparties.store'),
            [
                'inn' => '1234567892',
            ]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'name',
                'inn',
                'ogrn',
                'address',
                'user_id',
            ]);

        $this->assertDatabaseHas('counterparties', [
            'inn' => '1234567892',
            'user_id' => $user->id,
        ]);
    }

    public function testShouldResponseUnprocessableEntityForCreateCounterparty()
    {
        Http::fake([
            'https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party' => Http::response([
                'suggestions' => [
                    [
                        'value' => 'ПАО СБЕРБАНК',
                        'data' => [
                            'inn' => '12345678',
                            'name' => [
                                'short_with_opf' => 'ПАО СБЕРБАНК',
                            ],
                            'ogrn' => '1027700132195',
                            'address' => [
                                'unrestricted_value' => '117312, г Москва, ул Вавилова, д 19',
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $user = User::factory()->create(
            [
                'email' => 'test@example.com',
                'password' => 'password'
            ]
        );

        Sanctum::actingAs($user);

        $response = $this->postJson(
            route('counterparties.store'),
            [
                'inn' => 'dfs89dfdf',
            ]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('counterparties', [
            'inn' => 'dfs89dfdf',
            'user_id' => $user->id,
        ]);
    }

    public function testShouldResponseUnauthorizedForCreateCounterparty()
    {
        Http::fake([
            'https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party' => Http::response([
                'suggestions' => [
                    [
                        'value' => 'ПАО СБЕРБАНК',
                        'data' => [
                            'inn' => '12345678',
                            'name' => [
                                'short_with_opf' => 'ПАО СБЕРБАНК',
                            ],
                            'ogrn' => '1027700132195',
                            'address' => [
                                'unrestricted_value' => '117312, г Москва, ул Вавилова, д 19',
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $response = $this->postJson(
            route('counterparties.store'),
            [
                'inn' => 'dfs89dfdf',
            ]
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testShouldResponseOkForGetListCounterparty()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $counterparties = Counterparty::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->get(route('counterparties.index'));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'counterparties' => [
                '*' => [
                    'id',
                    'user_id',
                    'name',
                ],
            ],
        ]);

        $responseData = $response->json();

        $this->assertCount(3, $responseData['counterparties']);

        foreach ($responseData['counterparties'] as $counterpartyData) {
            $this->assertEquals($user->id, $counterpartyData['user_id']);
            $this->assertTrue($counterparties->contains('id', $counterpartyData['id']));
        }
    }

    public function testShouldResponseUnauthorizedForGetListCounterparty()
    {
        $response = $this->getJson(route('counterparties.index'));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
