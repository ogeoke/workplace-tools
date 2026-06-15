<?php

namespace App\Repositories\Implementations\Projects;

use App\Models\Projects\Project;
use App\Repositories\Implementations\BaseRepository;

class ProjectRepository extends BaseRepository
{
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function getByOrganization(int $organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('status', '!=', 'archived')
            ->with(['lead', 'tasks', 'sprints'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByTeam(int $teamId)
    {
        return $this->model
            ->where('team_id', $teamId)
            ->where('status', '!=', 'archived')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByLead(int $leadId)
    {
        return $this->model
            ->where('lead_id', $leadId)
            ->where('status', '!=', 'archived')
            ->get();
    }

    public function getActiveProjects(int $organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('status', 'active')
            ->count();
    }
}
