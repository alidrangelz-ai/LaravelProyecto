<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Note;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'notes_count' => Note::where('user_id', auth()->id())->count(),
            'pinned_notes_count' => Note::where('user_id', auth()->id())->where('is_pinned', true)->count(),
            'tasks_pending_count' => Task::where('user_id', auth()->id())->where('is_completed', false)->count(),
            'tasks_completed_count' => Task::where('user_id', auth()->id())->where('is_completed', true)->count(),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats
        ]);
    }
}
