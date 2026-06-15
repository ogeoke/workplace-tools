<?php

namespace App\Services\Operations;

use App\Models\Operations\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CompanyService
{
    public function createCompany(array $data): Company
    {
        return Company::create($data);
    }

    public function updateCompany(Company $company, array $data): Company
    {
        $company->update($data);
        return $company->refresh();
    }

    public function archiveCompany(Company $company): bool
    {
        return (bool) $company->update(['status' => 'inactive']);
    }

    public function assignAccountManager(Company $company, User $user): Company
    {
        $company->update(['account_manager_id' => $user->id]);
        return $company->refresh();
    }

    public function getCompaniesByOrganization(int $organizationId): Collection
    {
        return Company::where('organization_id', $organizationId)
            ->where('status', 'active')
            ->with('accountManager')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getCompanyStats(Company $company): array
    {
        return [
            'total_clients' => $company->clients()->count(),
            'active_clients' => $company->clients()->where('contract_status', 'active')->count(),
            'total_invoices' => $company->invoices()->count(),
            'pending_invoices' => $company->invoices()->where('payment_status', '!=', 'paid')->count(),
            'total_assets' => $company->assets()->count(),
        ];
    }

    public function searchCompanies(string $query, int $organizationId): Collection
    {
        return Company::where('organization_id', $organizationId)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get();
    }
}
