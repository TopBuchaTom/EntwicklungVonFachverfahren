<?php

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $location = fake()->city();
        $start = now()
            ->setHour(fake()->numberBetween(8, 14))
            ->setMinute(fake()->numberBetween(0, 12) * 5)
            ->setSecond(0)
            ->addDays(fake()->numberBetween(10, 100));
        $end  = $start->addHours(fake()->numberBetween(1, 30));
        $amount = fake()->numberBetween(80, 250);
        $details = "Some explanation of amount";

        return [
            Travel::TITLE => "Dienstreise nach $location",
            Travel::LOCATION => $location,
            Travel::START => $start,
            Travel::END => $end,
            Travel::AMOUNT => $amount,
            Travel::DETAILS => $details,
            Travel::APPLICANT_ID => fake()->numberBetween(1, 200)
        ];
    }
}
