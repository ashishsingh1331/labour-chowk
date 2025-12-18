<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        $skills = Skill::query()->orderBy('name')->get();

        return view('admin.skills.index', [
            'skills' => $skills,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:80', 'unique:skills,name'],
        ]);

        Skill::create([
            'name' => trim($data['name']),
            'is_active' => true,
        ]);

        return back()->with('status', 'Skill added.');
    }

    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $skill->update([
            'is_active' => (bool) $data['is_active'],
        ]);

        return back()->with('status', 'Skill updated.');
    }
}
