<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Mason',
            'Painter',
            'Electrician Helper',
            'Plumber Helper',
            'Carpenter Helper',
            'General Labour',
        ];

        foreach ($names as $name) {
            Skill::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true]
            );
        }
    }
}
