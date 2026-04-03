<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index() {
    $tasks = Task::orderBy('created_at', 'desc')->get();
    return view('tasks.index', compact('tasks'));
}


    public function create() {
    return view('tasks.create');
}

public function store(Request $request) {
    $request->validate([
        'title' => 'required|max:255',
        'status' => 'required|in:todo,in_progress,done',
        'priority' => 'required|in:low,medium,high',
    ]);

    Task::create($request->all());

    return redirect()->route('tasks.index')->with('success', 'Tâche créée avec succés!');
}
}
