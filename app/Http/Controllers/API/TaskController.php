<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(true, "Task fetched successfully!", Task::select('name', 'description', 'user_id')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateTask = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'user_id' => 'required|integer|exists:users,id',
                'description' => 'nullable|string'
            ]
        );

        if ($validateTask->fails()) {
            return $this->sendError(false, $validateTask->errors());
        }


        try {
            $task = Task::create([
                'name' => $request->name,
                'user_id' => $request->user_id,
                "description" => $request->description
            ]);
            return $this->sendResponse(true, "Task created successfully!", $task);
        } catch (Exception $e) {
            return $this->sendError(false, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return $this->sendError(false, "Task not found", 401);
        }

        return $this->sendResponse(true, "Task retrieved successfully!", $task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return $this->sendError(false, "Task not found", 401);
        }

        $task->update([
            "name" => $request->name,
            "description" => $request->description ? $request->description : $task->description
        ]);

        return $this->sendResponse(false, "Task updated successfully!", $task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return $this->sendError(false, "Task not found", 401);
        }

        try {
            $task->delete();
            return $this->sendResponse(true, "Task deleted successfully!", null);
        } catch (Exception $e) {
            return $this->sendError(false, $e->getMessage());
        }
    }
}
