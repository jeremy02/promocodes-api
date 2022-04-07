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
     * Testing Add new promocode API endpoint
     *
     * @return void
     */
    public function testAddNewPromoCode() {
        $response = $this->post('/api/promocodes', [
            'title' => $this->faker->sentence(3),
            'code' => 'T45RIRIJ7C', // $this->faker->regexify('[A-Z0-9]{10}') // since we are testing
                        // we add a hard-coded code so that we can test adding of a promo code duplicate code
            'description' => $this->faker->sentence(),
            'discount_amount' => $this->faker->randomFloat(0,100, 1000),
            'radius' => $this->faker->randomFloat(0, 2, 50),
            'radius_unit' => 'km',
            'start_at' => now()->addMinutes(4),
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
}
