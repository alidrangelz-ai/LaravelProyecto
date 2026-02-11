<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Inertia\Inertia;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Settings/Index', [
            'settings' => Setting::where('user_id', auth()->id())->get()->keyBy('key')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable'
        ]);

        foreach ($validated['settings'] as $setting) {
            Setting::updateOrCreate(
                ['user_id' => auth()->id(), 'key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        return redirect()->back();
    }
    
    // Unused methods
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
