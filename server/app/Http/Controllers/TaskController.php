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
            
            // return response()->json();
            $tasks = Task::where('user_id', Auth::id())->get();
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
            'date' => 'nullable|date',
        ]);
        $task = Task::create([
            'title' => $request->title,
            'date' => $request->date,
            'user_id' => Auth::id(),
        ]);

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
        
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
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
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
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
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->update(['status' => 'complate']);
        return response()->json($task);
    }
    public function uncomplete($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->update(['status' => null]);
        return response()->json($task);
    }
}