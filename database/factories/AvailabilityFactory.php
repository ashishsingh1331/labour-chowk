<?php

namespace Database\Factories;

use App\Models\Labourer;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Availability>
 */
class AvailabilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'labourer_id' => Labourer::factory(),
            'date' => CarbonImmutable::today()->toDateString(),
            'status' => 'available',
        ];
    }
}
