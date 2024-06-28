<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks belonging to the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $tasks = Task::whereBelongsTo(Auth::user(),'user_id');
        return response()->json($tasks);
    }

    /**
     * Store a newly created task for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $task = Task::whereBelongsTo(Auth::user(),'user_id')->create($request->all());

        return response()->json($task, 201);
    }

    /**
     * Display the specified task belonging to the authenticated user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::whereBelongsTo(Auth::user(),'user_id')->findOrFail($id);
        return response()->json($task);
    }

    /**
     * Update the specified task belonging to the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $task = Task::whereBelongsTo(Auth::user(),'user_id')->findOrFail($id);
        $task->update($request->all());
        return response()->json($task);
    }

    /**
     * Remove the specified task belonging to the authenticated user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::whereBelongsTo(Auth::user(),'user_id')->findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }

    /**
     * Mark the specified task belonging to the authenticated user as complete.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function complete($id)
    {
        $task = Task::whereBelongsTo(Auth::user(),'user_id')->findOrFail($id);
        $task->update(['status' => 'complate']);
        return response()->json($task);
    }
    public function uncomplete($id)
    {
        $task = Task::whereBelongsTo(Auth::user(),'user_id')->findOrFail($id);
        $task->update(['status' => null]);
        return response()->json($task);
    }
}