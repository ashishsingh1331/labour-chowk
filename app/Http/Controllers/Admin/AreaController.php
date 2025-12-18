<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AreaController extends Controller
{
    public function index(): View
    {
        $areas = Area::query()->orderBy('name')->get();

        return view('admin.areas.index', [
            'areas' => $areas,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:80', 'unique:areas,name'],
        ]);

        Area::create([
            'name' => trim($data['name']),
            'is_active' => true,
        ]);

        return back()->with('status', 'Area added.');
    }

    public function update(Request $request, Area $area): RedirectResponse
    {
        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $area->update([
            'is_active' => (bool) $data['is_active'],
        ]);

        return back()->with('status', 'Area updated.');
    }
}
