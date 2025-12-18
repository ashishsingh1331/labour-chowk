<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Availability;
use App\Models\Labourer;
use App\Models\Skill;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AvailabilityTodayController extends Controller
{
    public function index(Request $request): View
    {
        $today = CarbonImmutable::today();

        $areaId = $request->integer('area_id');
        $skillId = $request->integer('skill_id');
        $q = trim((string) $request->query('q', ''));

        $base = Labourer::query()
            ->with(['area', 'skills'])
            ->where('is_active', true)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('full_name', 'like', "%{$q}%")
                        ->orWhere('phone_e164', 'like', "%{$q}%");
                });
            })
            ->when($areaId, fn ($query) => $query->where('area_id', $areaId))
            ->when($skillId, function ($query) use ($skillId) {
                $query->whereHas('skills', fn ($q2) => $q2->where('skills.id', $skillId));
            });

        $labourers = (clone $base)
            ->orderBy('full_name')
            ->paginate(25)
            ->withQueryString();

        $availableIds = Availability::query()
            ->whereDate('date', $today->toDateString())
            ->where('status', 'available')
            ->pluck('labourer_id')
            ->all();

        return view('admin.availability.today', [
            'today' => $today,
            'labourers' => $labourers,
            'availableIds' => array_fill_keys($availableIds, true),
            'areas' => Area::query()->where('is_active', true)->orderBy('name')->get(),
            'skills' => Skill::query()->where('is_active', true)->orderBy('name')->get(),
            'areaId' => $areaId,
            'skillId' => $skillId,
            'q' => $q,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:mark_available,remove'],
            'labourer_ids' => ['required', 'array', 'min:1'],
            'labourer_ids.*' => ['integer', 'exists:labourers,id'],
        ]);

        $today = CarbonImmutable::today()->toDateString();
        $ids = array_values(array_unique($data['labourer_ids']));

        if ($data['action'] === 'mark_available') {
            $rows = array_map(fn ($id) => [
                'labourer_id' => $id,
                'date' => $today,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ], $ids);

            // Upsert to enforce uniqueness on (labourer_id, date)
            DB::table('availabilities')->upsert(
                $rows,
                ['labourer_id', 'date'],
                ['status', 'updated_at']
            );

            return back()->with('status', 'Marked available for today.');
        }

        Availability::query()
            ->whereDate('date', $today)
            ->whereIn('labourer_id', $ids)
            ->delete();

        return back()->with('status', 'Removed availability for today.');
    }
}
