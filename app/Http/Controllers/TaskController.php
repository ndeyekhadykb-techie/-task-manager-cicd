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
    }


}
