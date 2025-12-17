<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLabourerRequest;
use App\Http\Requests\UpdateLabourerRequest;
use App\Models\Area;
use App\Models\Labourer;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LabourerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $areaId = $request->integer('area_id');

        $labourers = Labourer::query()
            ->with(['area', 'skills'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('full_name', 'like', "%{$q}%")
                        ->orWhere('phone_e164', 'like', "%{$q}%");
                });
            })
            ->when($areaId, fn ($query) => $query->where('area_id', $areaId))
            ->orderByDesc('is_active')
            ->orderBy('full_name')
            ->paginate(20)
            ->withQueryString();

        $areas = Area::query()->orderBy('name')->get();

        return view('admin.labourers.index', [
            'labourers' => $labourers,
            'areas' => $areas,
            'q' => $q,
            'areaId' => $areaId,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.labourers.create', [
            'areas' => Area::query()->where('is_active', true)->orderBy('name')->get(),
            'skills' => Skill::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLabourerRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $labourer = Labourer::create([
            'full_name' => $data['full_name'],
            'phone_e164' => $data['phone_e164'],
            'area_id' => $data['area_id'],
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        if ($request->hasFile('photo')) {
            $labourer->photo_path = $request->file('photo')->store('labourers', 'public');
            $labourer->save();
        }

        $skillIds = $data['skill_ids'] ?? [];
        $labourer->skills()->sync($skillIds);

        return redirect()
            ->route('admin.labourers.edit', $labourer)
            ->with('status', 'Labourer created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Labourer $labourer): RedirectResponse
    {
        return redirect()->route('admin.labourers.edit', $labourer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Labourer $labourer): View
    {
        $labourer->load(['area', 'skills']);

        return view('admin.labourers.edit', [
            'labourer' => $labourer,
            'areas' => Area::query()->orderBy('name')->get(),
            'skills' => Skill::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLabourerRequest $request, Labourer $labourer): RedirectResponse
    {
        $data = $request->validated();

        $labourer->fill([
            'full_name' => $data['full_name'],
            'phone_e164' => $data['phone_e164'],
            'area_id' => $data['area_id'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ])->save();

        if ($request->hasFile('photo')) {
            if ($labourer->photo_path) {
                Storage::disk('public')->delete($labourer->photo_path);
            }
            $labourer->photo_path = $request->file('photo')->store('labourers', 'public');
            $labourer->save();
        }

        $skillIds = $data['skill_ids'] ?? [];
        $labourer->skills()->sync($skillIds);

        return back()->with('status', 'Labourer updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Labourer $labourer): RedirectResponse
    {
        if ($labourer->photo_path) {
            Storage::disk('public')->delete($labourer->photo_path);
        }

        $labourer->skills()->detach();
        $labourer->delete();

        return redirect()
            ->route('admin.labourers.index')
            ->with('status', 'Labourer deleted.');
    }
}
