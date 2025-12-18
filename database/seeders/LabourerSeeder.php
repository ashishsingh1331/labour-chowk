<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Labourer;
use App\Models\Skill;
use Database\Seeders\Concerns\SeedsPhotos;
use Illuminate\Database\Seeder;

class LabourerSeeder extends Seeder
{
    use SeedsPhotos;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = Area::query()->where('is_active', true)->get();
        $skills = Skill::query()->where('is_active', true)->get();

        if ($areas->isEmpty() || $skills->isEmpty()) {
            return;
        }

        $photoSource = database_path('seeders/assets/labourers');

        Labourer::factory()
            ->count(60)
            ->make()
            ->each(function (Labourer $labourer) use ($areas, $skills, $photoSource) {
                $labourer->area_id = $areas->random()->id;

                // Copy a synthetic photo if available; otherwise leave null.
                $labourer->photo_path = $this->seedPhotoPoolToPublic($photoSource) ?? null;

                $labourer->save();

                $skillIds = $skills->random(rand(1, min(3, $skills->count())))->pluck('id')->all();
                $labourer->skills()->sync($skillIds);
            });
    }
}
