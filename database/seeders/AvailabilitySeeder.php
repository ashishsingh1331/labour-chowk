<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Labourer;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = CarbonImmutable::today()->toDateString();

        $labourers = Labourer::query()
            ->where('is_active', true)
            ->get();

        if ($labourers->isEmpty()) {
            return;
        }

        // Mark all active labourers as available today by default.
        foreach ($labourers as $labourer) {
            Availability::query()->updateOrCreate(
                ['labourer_id' => $labourer->id, 'date' => $today],
                ['status' => 'available']
            );
        }
    }
}
