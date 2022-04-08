<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PromoCodeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void {
        parent::setUp();
        Artisan::call('db:seed');
    }


    /**
     * Testing Add new Promo Code API
     *
     * @return void
     */
    public function testAddNewPromoCode() {
        $response = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
            ->post('/api/promocodes', [
                'title' => $this->faker->sentence(3),
                'code' => 'T45RIRIJ7C', // $this->faker->regexify('[A-Z0-9]{10}') // since we are testing
                            // we add a hard-coded code so that we can test adding of a promo code duplicate code
                'discount_amount' => $this->faker->randomFloat(0,100, 1000),
                'radius' => $this->faker->randomFloat(0, 2, 50),
                'radius_unit' => 'km',
                'start_at' => now(),
                'end_at' => now()->addHours(4),
            ]
        );

        $response->assertStatus(200); // the status to return
        $response->assertJsonStructure([  // the json to return
            'status',
            'message',
            'data' => [
                'id',
                'title',
                'discount_amount',
                'code',
                'radius',
                'radius_unit',
                'start_at',
                'end_at',
                'is_used',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ]
        ]);
    }

    /**
     * Testing Add new Promo Code API with invalid input
     *
     * @return void
     */
    public function testAddNewPromoCodeWithInvalidInput() {
        $invalidResponse = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
            ->post('/api/promocodes', [
                'title' => $this->faker->sentence(3),
                'code' => $this->faker->regexify('[A-Z0-9]{10}'),
                'discount_amount' => $this->faker->randomFloat(0,100, 1000),
                'radius' => $this->faker->randomFloat(0, 2, 50),
                'radius_unit' => 'cm', // add centimetres is an invalid input
                'start_at' => now()->addMinutes(3), // invalid input
                'end_at' => now()->subHours(3), // invalid input
            ]
        );

        $invalidResponse->assertStatus(422);
        $invalidResponse->assertJsonStructure([
            'errors',
            'message',
        ]);
    }

    /**
     * Testing Add new Promo Code API with Duplicate Code
     *
     * @return void
     */
    public function testAddNewPromoCodeWithDuplicateCode() {
        $duplicateCodeResponse = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
            ->post('/api/promocodes',
            [
                'title' => $this->faker->sentence(3),
                'code' => 'T45RIRIJ7C', // $this->faker->regexify('[A-Z0-9]{10}') // since we are testing
                // we add a hard-coded code so that we can test adding of a promo code duplicate code
                'discount_amount' => $this->faker->randomFloat(0,100, 1000),
                'radius' => $this->faker->randomFloat(0, 2, 50),
                'radius_unit' => 'km',
                'start_at' => now()->subMinutes(4),
                'end_at' => now()->addHours(4),
            ]
        );

        $duplicateCodeResponse->assertStatus(200);
        $duplicateCodeResponse->assertJsonStructure([
            'status',
            'message',
        ]);
    }

    /**
     * Testing Get All promo codes API
     *
     * @return void
     */
    public function testGetAllPromoCodes() {
        $response = $this->withHeaders(['Accept' => 'application/json'])->get('/api/promocodes');

        $response->assertStatus(200);  // the status to return
        $response->assertJsonStructure([ // the json to return
            'status',
            'data' => [
                'data' => [
                    [
                        'id',
                        'title',
                        'code',
                        'discount_amount',
                        'radius',
                        'radius_unit',
                        'start_at',
                        'end_at',
                        'is_used',
                        'is_active',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ],
                'meta' => [
                    'current_page',
                    'total',
                    'count',
                    'per_page',
                    'total_pages',
                ],
            ]
        ]);
    }



    /**
     * Testing Get A Promo Code API using {id}
     *
     * @return void
     */
    public function testGetAPromoCode() {
        // let first create a promo code that has status in-active
        $response = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
            ->post('/api/promocodes',
            [
                'title' => $this->faker->sentence(3),
                'code' => $this->faker->regexify('[A-Z0-9]{10}'),
                'discount_amount' => $this->faker->randomFloat(0,100, 1000),
                'radius' => $this->faker->randomFloat(0, 2, 50),
                'radius_unit' => 'km',
                'start_at' => now()->subMinutes(20),
                'end_at' => now()->addHours(2),// the end_at must be greater than start_at // invalid input
                'is_active' => false,
            ]
        );

        // the newly created promo code
        $newPromoCodeData = $response->json();

        // now call the endpoint activate/{id}
        $promoCodeResponse = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/promocodes/'. $newPromoCodeData['data']['id']);

        $promoCodeResponse->assertStatus(200);  // the status to return
        $promoCodeResponse->assertJsonStructure([ // the json to return
            'status',
            "message",
            'promocode' => [
                'id',
                'title',
                'discount_amount',
                'code',
                'radius',
                'radius_unit',
                'start_at',
                'end_at',
                'is_used',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ]
        ]);
    }/**
 * Testing Update Promo Code API
 *
 * @return void
 */
    public function testUpdatePromoCode() {
        // let first create a promo code
        $response = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
        ->post('/api/promocodes', [
            'title' => $this->faker->sentence(3),
            'code' => $this->faker->regexify('[A-Z0-9]{10}'),
            'discount_amount' => $this->faker->randomFloat(0,100, 1000),
            'radius' => 3,
            'radius_unit' => 'km',
            'start_at' => now()->subMinutes(2),
            'end_at' => now()->addMonths(2),
        ]);

        // the newly created promo code
        $newPromoCodeData = $response->json();

        $updateResponse = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
        ->put('/api/promocodes/'.$newPromoCodeData['data']['id'],
            [
                'title' => 'Edited Promo Code 21km',
                'radius' => 21,
                'radius_unit' => 'km',
                'code' => 'RIDE21KMFREE',
                'discount_amount' => 900,
                'start_at' => now(),
                'end_at' => now()->addWeeks(2),
            ]
        );

        $updateResponse->assertStatus(200); // the status to return
        $updateResponse->assertJsonStructure([  // the json to return
            'status',
            'message',
            'data' => [
                'id',
                'title',
                'discount_amount',
                'code',
                'radius',
                'radius_unit',
                'start_at',
                'end_at',
                'is_used',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ]
        ]);
    }

    /**
     * Testing Delete Promo Code API
     *
     * @return void
     */
    public function testDeletePromoCode() {
        // let first create a promo code
        $response = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
        ->post('/api/promocodes',
            [
                'title' => $this->faker->sentence(3),
                'code' => $this->faker->regexify('[A-Z0-9]{10}'),
                'discount_amount' => $this->faker->randomFloat(0,100, 1000),
                'radius' => 3,
                'radius_unit' => 'km',
                'start_at' => now()->subMinutes(2),
                'end_at' => now()->addMonths(2),
            ]
        );

        // the newly created promo code
        $newPromoCodeData = $response->json();

        $deleteResponse = $this->withHeaders(['Accept' => 'application/json'])
            ->delete('/api/promocodes/'.$newPromoCodeData['data']['id']);

        $deleteResponse->assertStatus(200); // the status to return
        $deleteResponse->assertJsonStructure([ // the json to return
            'status',
            'message',
        ]);
    }

    /**
     * Testing Activate Promo Code API
     *
     * @return void
     */
    public function testActivatePromoCode() {
        // let first create a promo code
        $response = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
             ->post('/api/promocodes',
            [
                'title' => $this->faker->sentence(3),
                'code' => $this->faker->regexify('[A-Z0-9]{10}'),
                'discount_amount' => $this->faker->randomFloat(0,100, 1000),
                'radius' => $this->faker->randomFloat(0, 2, 50),
                'radius_unit' => 'km',
                'start_at' => now()->subMinutes(2),
                'end_at' => now()->addHours(2),
                'is_active' => false,
            ]
        );

        // the newly created promo code
        $newPromoCodeData = $response->json();

        // now call the endpoint activate/{id}
        $activateResponse = $this->withHeaders(['Accept' => 'application/json'])
            ->put('/api/promocodes/activate/'. $newPromoCodeData['data']['id']);

        $activateResponse->assertStatus(200);  // the status to return
        $activateResponse->assertJsonStructure([ // the json to return
            'status',
            'message',
        ]);
    }

    /**
     * Testing Deactivate Promo Code API
     *
     * @return void
     */
    public function testDeactivatePromoCode() {
        // let first create a promo code  which by default has status is_active as true
        $response = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
        ->post('/api/promocodes',
            [
                'title' => $this->faker->sentence(3),
                'code' => $this->faker->regexify('[A-Z0-9]{10}'),
                'discount_amount' => $this->faker->randomFloat(0,100, 1000),
                'radius' => $this->faker->randomFloat(0, 2, 50),
                'radius_unit' => 'km',
                'start_at' => now()->subMinutes(2),
                'end_at' => now()->addHours(2)
            ]
        );

        // the newly created promo code
        $newPromoCodeData = $response->json();

        // now call the endpoint deactivate/{id}
        $deActivateResponse = $this->withHeaders(['Accept' => 'application/json'])
            ->put('/api/promocodes/deactivate/'.$newPromoCodeData['data']['id']);

        $deActivateResponse->assertStatus(200);
        $deActivateResponse->assertJsonStructure([
            'status',
            'message',
        ]);
    }

    /**
     * Testing Get Active Promo Codes API
     *
     * @return void
     */
    public function testGetActivePromoCodes() {
        $response = $this->withHeaders(['Accept' => 'application/json'])->get('/api/promocodes/active');

        $response->assertStatus(200);  // the status to return
        $response->assertJsonStructure([ // the json to return
            'status',
            'data' => [
                'data' => [
                    [
                        'id',
                        'title',
                        'code',
                        'discount_amount',
                        'radius',
                        'radius_unit',
                        'start_at',
                        'end_at',
                        'is_used',
                        'is_active',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ],
                'meta' => [
                    'current_page',
                    'total',
                    'count',
                    'per_page',
                    'total_pages',
                ],
            ]
        ]);
    }

    /**
     * Testing Get In-Active Promo Codes API
     *
     * @return void
     */
    public function testGetInActivePromoCodes() {
        // let first create a promo code that has status in-active
        $response = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
        ->post('/api/promocodes',
            [
                'title' => $this->faker->sentence(3),
                'code' => $this->faker->regexify('[A-Z0-9]{10}'),
                'discount_amount' => $this->faker->randomFloat(0,100, 1000),
                'radius' => $this->faker->randomFloat(0, 2, 50),
                'radius_unit' => 'km',
                'start_at' => now()->subMinutes(2),
                'end_at' => now()->addHours(2),// the end_at must be greater than start_at // invalid input
                'is_active' => false,
            ]
        );

        //  First we have to create a promo code that status is_active to catch the error
        // 'asserting that an array has the key 0'
        $inActivePromoCodesResponse = $this->withHeaders(['Accept' => 'application/json'])->get('/api/promocodes/inactive');

        $inActivePromoCodesResponse->assertStatus(200);  // the status to return
        $inActivePromoCodesResponse->assertJsonStructure([ // the json to return
            'status',
            'data' => [
                'data' => [
                    [
                        'id',
                        'title',
                        'code',
                        'discount_amount',
                        'radius',
                        'radius_unit',
                        'start_at',
                        'end_at',
                        'is_used',
                        'is_active',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ],
                'meta' => [
                    'current_page',
                    'total',
                    'count',
                    'per_page',
                    'total_pages',
                ],
            ]
        ]);
    }

    /**
     * Testing Check Promo Code is Valid API
     *
     * @return void
     */
    public function testCheckValidPromoCode() {
        // let first create a promo code
        $response = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
        ->post('/api/promocodes',
            [
                'title' => $this->faker->sentence(3),
                'code' => $this->faker->regexify('[A-Z0-9]{10}'),
                'discount_amount' => $this->faker->randomFloat(0,100, 1000),
                'radius' => 15,
                'radius_unit' => 'km',
                'start_at' => now()->subMinutes(2),
                'end_at' => now()->addMonths(2),
            ]
        );

        // the newly created promo code
        $newPromoCodeData = $response->json();

        // add demo origin and destination LatLng
        $checkValidResponse = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
        ->post('/api/promocodes/checkvalid',
            [
                'origin_latitude' => -1.2999905808760437,
                'origin_longitude' => 36.80693232888409,
                'destination_latitude' => -1.2695562226811647,
                'destination_longitude' => 36.82263241065327,
                'code' => $newPromoCodeData['data']['code']
            ]
        );

        $checkValidResponse->assertStatus(200);  // the status to return
        $checkValidResponse->assertJsonStructure([  // the json to return
            'status',
            'message',
            'data' => [
                'promocode' => [
                    'id',
                    'title',
                    'discount_amount',
                    'code',
                    'radius',
                    'radius_unit',
                    'start_at',
                    'end_at',
                    'is_used',
                    'is_active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ],
                'routes' => [
                    [
                        'bounds',
                        'copyrights',
                        'legs' => [
                            [
                                'distance',
                                'duration',
                                'end_address',
                                'end_location',
                                'start_address',
                                'start_location',
                                'steps' => [
                                    [
                                        'distance',
                                        'duration',
                                        'end_location',
                                        'html_instructions',
                                        'polyline',
                                        'start_location',
                                        'travel_mode',
                                    ]
                                ],
                                'traffic_speed_entry',
                                'via_waypoint',
                            ]
                        ],
                        'overview_polyline' => [
                            'points' => [
                                [
                                    'lat',
                                    'lng',
                                ]
                            ]
                        ],
                        'summary',
                        'warnings',
                        'waypoint_order',
                    ]
                ]
            ]
        ]);
    }



    /**
     * Testing Check Promo Code is In-valid API
     *
     * @return void
     */
    public function testCheckInValidPromoCode() {
        // let first create a promo code
        $response = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
        ->post('/api/promocodes',
            [
                'title' => $this->faker->sentence(3),
                'code' => $this->faker->regexify('[A-Z0-9]{10}'),
                'discount_amount' => $this->faker->randomFloat(0,100, 1000),
                'radius' => 3,
                'radius_unit' => 'km',
                'start_at' => now()->subMinutes(2),
                'end_at' => now()->addMonths(2),
            ]
        );

        // the newly created promo code
        $newPromoCodeData = $response->json();

        // origin and destination in the indian ocean
        $checkInValidResponse = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
            ->post('/api/promocodes/checkvalid', [
            'origin_latitude' => -3.5074999085744194,
            'origin_longitude' => 43.478856076740485,
            'destination_latitude' => 0.991341459044253,
            'destination_longitude' => 45.67390505241324,
            'code' => $newPromoCodeData['data']['code']
        ]);

        $checkInValidResponse->assertStatus(400); // the status to return
        $checkInValidResponse->assertJsonStructure([  // the json to return
            'status',
            'message',
        ]);
    }
}
