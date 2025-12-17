<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Labourer;
use App\Models\Skill;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class BrowseController extends Controller
{
    public function index(Request $request): View
    {
        $today = CarbonImmutable::today()->toDateString();

        $areaId = $request->integer('area');
        $skillIds = array_values(array_filter((array) $request->query('skills', [])));
        $q = trim((string) $request->query('q', ''));

        $areas = Cache::remember('browse:areas', 60, fn () => Area::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get());

        $skills = Cache::remember('browse:skills', 60, fn () => Skill::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get());

        $results = null;

        if ($areaId) {
            $results = Labourer::query()
                ->with(['skills', 'area'])
                ->where('is_active', true)
                ->where('area_id', $areaId)
                ->whereHas('availabilities', function ($q2) use ($today) {
                    $q2->whereDate('date', $today)->where('status', 'available');
                })
                ->when($q !== '', fn ($query) => $query->where('full_name', 'like', "%{$q}%"))
                ->when(count($skillIds) > 0, function ($query) use ($skillIds) {
                    $query->whereHas('skills', fn ($q2) => $q2->whereIn('skills.id', $skillIds));
                })
                ->orderBy('full_name')
                ->paginate(25)
                ->withQueryString();
        }

        return view('browse.index', [
            'today' => $today,
            'areas' => $areas,
            'skills' => $skills,
            'results' => $results,
            'areaId' => $areaId,
            'skillIds' => array_map('intval', $skillIds),
            'q' => $q,
        ]);
    }
}
