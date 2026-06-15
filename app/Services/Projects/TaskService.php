<?php

namespace App\Services\Projects;

use App\Models\Projects\Task;
use App\Models\Projects\Project;
use App\Models\User;
use App\Events\Projects\TaskCreated;
use App\Events\Projects\TaskAssigned;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function createTask(Project $project, array $data): Task
    {
        $data['project_id'] = $project->id;
        $data['created_by'] = auth()->id();
        $task = Task::create($data);
        TaskCreated::dispatch($task);
        return $task->load(['assignee', 'sprint']);
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->refresh();
    }

    public function assignTask(Task $task, User $user): Task
    {
        $task->update(['assigned_to' => $user->id]);
        TaskAssigned::dispatch($task, $user);
        return $task->refresh();
    }

    public function changeStatus(Task $task, string $status): Task
    {
        $task->update(['status' => $status]);
        if ($status === 'done') {
            $task->update(['completed_at' => now()]);
        }
        return $task->refresh();
    }

    public function getTasksByProject(Project $project): Collection
    {
        return $project->tasks()
            ->with(['assignee', 'sprint'])
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->get();
    }

    public function getTasksByStatus(Project $project, string $status): Collection
    {
        return $project->tasks()
            ->where('status', $status)
            ->with(['assignee', 'sprint'])
            ->get();
    }

    public function getOverdueTasks(Project $project): Collection
    {
        return $project->tasks()
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['done'])
            ->get();
    }

    public function getTasksByUser(User $user): Collection
    {
        return Task::where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->orderBy('due_date', 'asc')
            ->get();
    }
}
