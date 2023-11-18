<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Task::all();
        return response()->json(['message' => 'all data Task', 'original_code' => 200, 'data' => $data], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validated = Validator::make($request->all(), [
                'title' => 'required',
                'summary' => 'required'
            ]);

            if ($validated->fails()) {
                return response()->json(['error' => $validated->getMessageBag()], 500);
            }
            $data = Task::create([
                'title' => $request->title,
                'summary' => $request->summary
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], 500);
        }

        return response()->json(['message' => 'success created data Task', 'original_code' => 200, 'data' => $data], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json(['message' => 'data below', 'data' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        try {
            DB::beginTransaction();
            $validated = Validator::make($request->all(), [
                'title' => 'required',
                'summary' => 'required'
            ]);

            if ($validated->fails()) {
                return response()->json(['error' => $validated->getMessageBag()], 500);
            }
            $task->title = $request->title;
            $task->summary = $request->summary;
            if ($request->status) {
                $task->status = $request->status;
            }

            $task->save();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], 500);
        }

        return response()->json(['message' => 'success update data Task', 'original_code' => 200, 'data' => $task], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            DB::beginTransaction();
            $task->delete();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], 500);
        }

        return response()->json(['message' => 'success deleted data Task', 'original_code' => 200, 'data' => $task], 200);
    }
}
