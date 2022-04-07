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
            'description' => $this->faker->sentence(),
            'discount_amount' => $this->faker->randomFloat(0,100, 1000),
            'radius' => $this->faker->randomFloat(0, 2, 50),
            'radius_unit' => 'km',
            'start_at' => now(),
            'end_at' => now()->addHours(4),
        ]);

        $response->assertStatus(200); // the status to return
        $response->assertJsonStructure([  // the json to return
            'status',
            'message',
            'data' => [
                'id',
                'title',
                'description',
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
            'description' => $this->faker->sentence(),
            'discount_amount' => $this->faker->randomFloat(0,100, 1000),
            'radius' => $this->faker->randomFloat(0, 2, 50),
            'radius_unit' => 'cm', // add centimetres is an invalid input
            'start_at' => now()->addMinutes(3), // invalid input
            'end_at' => now()->subHours(3), // invalid input
        ]);

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
            ->post('/api/promocodes', [
            'title' => $this->faker->sentence(3),
            'code' => 'T45RIRIJ7C', // $this->faker->regexify('[A-Z0-9]{10}') // since we are testing
            // we add a hard-coded code so that we can test adding of a promo code duplicate code
            'description' => $this->faker->sentence(),
            'discount_amount' => $this->faker->randomFloat(0,100, 1000),
            'radius' => $this->faker->randomFloat(0, 2, 50),
            'radius_unit' => 'km',
            'start_at' => now()->subMinutes(4),
            'end_at' => now()->addHours(4),
        ]);

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
        $response = $this->get('/api/promocodes');

        $response->assertStatus(200);  // the status to return
        $response->assertJsonStructure([ // the json to return
            'status',
            'data' => [
                'data' => [
                    [
                        'id',
                        'title',
                        'code',
                        'description',
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
     * Testing Activate Promo Code API
     *
     * @return void
     */
    public function testActivatePromoCode() {
        // let first create a promo code
        $response = $this->withHeaders(['Accept' => 'application/json']) // to make this a json request
             ->post('/api/promocodes', [
            'title' => $this->faker->sentence(3),
            'code' => $this->faker->regexify('[A-Z0-9]{10}'),
            'description' => $this->faker->sentence(),
            'discount_amount' => $this->faker->randomFloat(0,100, 1000),
            'radius' => $this->faker->randomFloat(0, 2, 50),
            'radius_unit' => 'km',
            'start_at' => now()->subMinutes(2),
            'end_at' => now()->addHours(2),
            'is_active' => false,
        ]);

        // the newly created promo code
        $newPromoCodeData = $response->json();

        // now call the endpoint activate/{id}
        $activateResponse = $this->put('/api/promocodes/activate/'. $newPromoCodeData['data']['id']);

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
        ->post('/api/promocodes', [
            'title' => $this->faker->sentence(3),
            'code' => $this->faker->regexify('[A-Z0-9]{10}'),
            'description' => $this->faker->sentence(),
            'discount_amount' => $this->faker->randomFloat(0,100, 1000),
            'radius' => $this->faker->randomFloat(0, 2, 50),
            'radius_unit' => 'km',
            'start_at' => now()->subMinutes(2),
            'end_at' => now()->addHours(2)
        ]);

        // the newly created promo code
        $newPromoCodeData = $response->json();

        // now call the endpoint deactivate/{id}
        $deActivateResponse = $this->put('/api/promocodes/deactivate/'.$newPromoCodeData['data']['id']);

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
        $response = $this->get('/api/promocodes/active');

        $response->assertStatus(200);  // the status to return
        $response->assertJsonStructure([ // the json to return
            'status',
            'data' => [
                'data' => [
                    [
                        'id',
                        'title',
                        'code',
                        'description',
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
        ->post('/api/promocodes', [
            'title' => $this->faker->sentence(3),
            'code' => $this->faker->regexify('[A-Z0-9]{10}'),
            'description' => $this->faker->sentence(),
            'discount_amount' => $this->faker->randomFloat(0,100, 1000),
            'radius' => $this->faker->randomFloat(0, 2, 50),
            'radius_unit' => 'km',
            'start_at' => now()->subMinutes(2),
            'end_at' => now()->addHours(2),// the end_at must be greater than start_at // invalid input
            'is_active' => false,
        ]);

        //  First we have to create a promo code that status is_active to catch the error
        // 'asserting that an array has the key 0'
        $inActivePromoCodesResponse = $this->get('/api/promocodes/inactive');

        $inActivePromoCodesResponse->assertStatus(200);  // the status to return
        $inActivePromoCodesResponse->assertJsonStructure([ // the json to return
            'status',
            'data' => [
                'data' => [
                    [
                        'id',
                        'title',
                        'code',
                        'description',
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
            ->post('/api/promocodes', [
            'title' => $this->faker->sentence(3),
            'code' => $this->faker->regexify('[A-Z0-9]{10}'),
            'description' => $this->faker->sentence(),
            'discount_amount' => $this->faker->randomFloat(0,100, 1000),
            'radius' => 15,
            'radius_unit' => 'km',
            'start_at' => now()->subMinutes(2),
            'end_at' => now()->addMonths(2),
        ]);

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

        $checkValidResponse->assertStatus(200);
        $checkValidResponse->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'promocode' => [
                    'id',
                    'title',
                    'description',
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
}
