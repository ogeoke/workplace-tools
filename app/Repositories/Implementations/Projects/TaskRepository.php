<?php

namespace App\Repositories\Implementations\Projects;

use App\Models\Projects\Task;
use App\Repositories\Implementations\BaseRepository;

class TaskRepository extends BaseRepository
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    public function getByProject(int $projectId)
    {
        return $this->model
            ->where('project_id', $projectId)
            ->with(['assignee', 'sprint', 'project'])
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->get();
    }

    public function getByStatus(int $projectId, string $status)
    {
        return $this->model
            ->where('project_id', $projectId)
            ->where('status', $status)
            ->with(['assignee', 'sprint'])
            ->get();
    }

    public function getAssignedToUser(int $userId)
    {
        return $this->model
            ->where('assigned_to', $userId)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    public function getOverdueTasks(int $projectId)
    {
        return $this->model
            ->where('project_id', $projectId)
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['done'])
            ->get();
    }

    public function getDueThisWeek(int $projectId)
    {
        return $this->model
            ->where('project_id', $projectId)
            ->whereBetween('due_date', [now(), now()->addWeek()])
            ->whereNotIn('status', ['done'])
            ->get();
    }
}
