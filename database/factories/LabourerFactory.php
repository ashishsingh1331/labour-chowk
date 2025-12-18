<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Labourer>
 */
class LabourerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phone = '+91' . $this->faker->numberBetween(6000000000, 9999999999);

        return [
            'full_name' => $this->faker->name(),
            'phone_e164' => $phone,
            'area_id' => Area::factory(),
            'photo_path' => null,
            'is_active' => true,
        ];
    }
}
