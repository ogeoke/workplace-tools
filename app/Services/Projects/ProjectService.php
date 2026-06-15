<?php

namespace App\Services\Projects;

use App\Models\Projects\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    public function createProject(array $data): Project
    {
        return Project::create($data);
    }

    public function updateProject(Project $project, array $data): Project
    {
        $project->update($data);
        return $project->refresh();
    }

    public function archiveProject(Project $project): bool
    {
        return (bool) $project->update(['status' => 'archived']);
    }

    public function getProjectsByOrganization(int $organizationId): Collection
    {
        return Project::where('organization_id', $organizationId)
            ->where('status', '!=', 'archived')
            ->with(['lead', 'tasks', 'sprints'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getProjectsByTeam(int $teamId): Collection
    {
        return Project::where('team_id', $teamId)
            ->where('status', '!=', 'archived')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getProjectStats(Project $project): array
    {
        $tasks = $project->tasks()->get();
        return [
            'total_tasks' => $tasks->count(),
            'completed_tasks' => $tasks->where('status', 'done')->count(),
            'active_sprints' => $project->sprints()->where('status', 'active')->count(),
            'team_members' => $project->team?->members->count() ?? 0,
        ];
    }
}
