<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request) {
        $status = $request->query('status');
        $allTasks = Task::all();

        $query = Task::orderBy('created_at', 'desc');
        if ($status && in_array($status, ['todo', 'in_progress', 'done'])) {
            $query->where('status', $status);
        }
        $tasks = $query->get();

        return view('tasks.index', compact('tasks', 'allTasks', 'status'));
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




public function show(Task $task){

    return view('tasks.show', compact('task'));
}