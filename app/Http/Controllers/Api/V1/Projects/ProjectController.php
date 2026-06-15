<?php

namespace App\Http\Controllers\Api\V1\Projects;

use App\Http\Controllers\Controller;
use App\Models\Projects\Project;
use App\Services\Projects\ProjectService;
use App\Http\Requests\Projects\StoreProjectRequest;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function __construct(private ProjectService $service) {}

    public function index(): JsonResponse
    {
        $orgId = auth()->user()->organizations()->first()->id;
        $projects = $this->service->getProjectsByOrganization($orgId);
        return response()->json([
            'success' => true,
            'data' => $projects,
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $project = $this->service->createProject($data);
        
        return response()->json([
            'success' => true,
            'data' => $project,
            'message' => 'Project created successfully',
        ], 201);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $project->load(['lead', 'tasks', 'sprints', 'team']),
            'stats' => $this->service->getProjectStats($project),
        ]);
    }

    public function update(StoreProjectRequest $request, Project $project): JsonResponse
    {
        $updated = $this->service->updateProject($project, $request->validated());
        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'Project updated successfully',
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->service->archiveProject($project);
        return response()->json([
            'success' => true,
            'message' => 'Project archived successfully',
        ]);
    }
}
