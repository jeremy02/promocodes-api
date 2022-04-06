<?php

namespace Database\Factories;

use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromocodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromoCode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'code' => $this->faker->regexify('[A-Z0-9]{10}'),
            'description' => $this->faker->sentence(),
            'discount_amount' => $this->faker->randomFloat(0,100, 1000),
            'radius' => $this->faker->randomFloat(0, 2, 50),
            'radius_unit' => 'km',
            'start_at' => now(),
            'end_at' => now()->addMonth(),
        ];
    }
}
