<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tasks,name',
        ]);

        $task = Task::create([
            'name' => $request->name,
        ]);

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['success' => true]);
    }

    public function complete(Task $task)
    {
        $task->update(['completed' => true]);  

        return response()->json(['success' => true]);
    }
}
