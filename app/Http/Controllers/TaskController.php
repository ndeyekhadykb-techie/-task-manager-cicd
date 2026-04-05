<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    // Liste des tâches
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $allTasks = Task::all();

        $query = Task::orderBy('created_at', 'desc');

        if ($status && in_array($status, ['todo', 'in_progress', 'done'])) {
            $query->where('status', $status);
        }

        $tasks = $query->get();

        return view('tasks.index', compact('tasks', 'allTasks', 'status'));
        // L'erreur était ici : tu avais un deuxième return redirect juste après le premier return view.
    }

    // Afficher formulaire création
    public function create(): View
    {
        return view('tasks.create');
    }

    // Enregistrer nouvelle tâche
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:todo,in_progress,done',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
        ]);

        Task::create($request->all());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tâche créée avec succès.');
    }

    // Formulaire modification
    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    // Mise à jour
    public function update(Request $request, Task $task): RedirectResponse
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:todo,in_progress,done',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
        ]);

        $task->update($request->all());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tâche mise à jour avec succès.');
    }

    public function show(Task $task): View
    {
        return view('tasks.show', compact('task'));
    }

    // Supprimer
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tâche supprimée avec succès.');
    }
}