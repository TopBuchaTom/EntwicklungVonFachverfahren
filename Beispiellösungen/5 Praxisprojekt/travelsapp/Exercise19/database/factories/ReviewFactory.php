<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\ReviewState;
use App\Models\ReviewType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Review::TRAVEL_ID => fake()->numberBetween(1, 10),
            Review::USER_ID => fake()->numberBetween(1, 200),
            Review::TYPE => ReviewType::cases()[fake()->numberBetween(0, 2)]->value,
            Review::STATE => ReviewState::cases()[fake()->numberBetween(0, 2)]->value,
            Review::CHANGED => fake()->numberBetween(0, 1),
            Review::COMMENT => fake()->text()
        ];
    }
}
