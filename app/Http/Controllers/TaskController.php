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

 /**
 * Affiche le formulaire de modification
 */
public function edit(Task $task): View
{
    return view('tasks.edit', compact('task'));
}

/**
 * Enregistre les modifications
 */
public function update(Request $request, Task $task): RedirectResponse
{
    $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'status'      => 'required|in:todo,in_progress,done',
        'priority'    => 'required|in:low,medium,high',
        'due_date'    => 'nullable|date',
    ]);

    $task->update($request->only(['title', 'description', 'status', 'priority', 'due_date']));

    return redirect()
        ->route('tasks.show', $task)
        ->with('success', 'Tâche mise à jour avec succès.');
}
}

