<?php

namespace App\Repositories\Implementations\Operations;

use App\Models\Operations\Company;
use App\Repositories\Implementations\BaseRepository;

class CompanyRepository extends BaseRepository
{
    public function __construct(Company $model)
    {
        parent::__construct($model);
    }

    public function getByOrganization(int $organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getByAccountManager(int $managerId)
    {
        return $this->model
            ->where('account_manager_id', $managerId)
            ->where('status', 'active')
            ->get();
    }

    public function searchByName(string $search, int $organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('name', 'LIKE', '%' . $search . '%')
            ->limit(10)
            ->get();
    }

    public function getTotalCompanies(int $organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('status', 'active')
            ->count();
    }
}
