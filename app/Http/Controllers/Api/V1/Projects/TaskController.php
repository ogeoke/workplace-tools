<?php

namespace App\Http\Controllers\Api\V1\Projects;

use App\Http\Controllers\Controller;
use App\Models\Projects\Project;
use App\Models\Projects\Task;
use App\Services\Projects\TaskService;
use App\Http\Requests\Projects\StoreTaskRequest;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(private TaskService $service) {}

    public function index(Project $project): JsonResponse
    {
        $tasks = $this->service->getTasksByProject($project);
        return response()->json([
            'success' => true,
            'data' => $tasks,
        ]);
    }

    public function store(StoreTaskRequest $request, Project $project): JsonResponse
    {
        $data = $request->validated();
        $task = $this->service->createTask($project, $data);
        
        return response()->json([
            'success' => true,
            'data' => $task,
            'message' => 'Task created successfully',
        ], 201);
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $task->load(['assignee', 'sprint', 'project', 'subtasks', 'worklogs']),
        ]);
    }

    public function update(StoreTaskRequest $request, Task $task): JsonResponse
    {
        $updated = $this->service->updateTask($task, $request->validated());
        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'Task updated successfully',
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ]);
    }
}
