<?php

namespace App\Services\Operations;

use App\Models\Operations\Client;
use App\Models\Operations\Company;
use Illuminate\Database\Eloquent\Collection;

class ClientService
{
    public function createClient(array $data): Client
    {
        return Client::create($data);
    }

    public function updateClient(Client $client, array $data): Client
    {
        $client->update($data);
        return $client->refresh();
    }

    public function linkToCompany(Client $client, Company $company): Client
    {
        $client->update(['company_id' => $company->id]);
        return $client->refresh();
    }

    public function getClientsByOrganization(int $organizationId): Collection
    {
        return Client::where('organization_id', $organizationId)
            ->with(['company', 'invoices'])
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getClientsByCompany(Company $company): Collection
    {
        return $company->clients()
            ->where('contract_status', 'active')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getActiveClients(int $organizationId): int
    {
        return Client::where('organization_id', $organizationId)
            ->where('contract_status', 'active')
            ->count();
    }

    public function searchClients(string $query, int $organizationId): Collection
    {
        return Client::where('organization_id', $organizationId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                    ->orWhere('email', 'LIKE', '%' . $query . '%')
                    ->orWhere('phone', 'LIKE', '%' . $query . '%');
            })
            ->limit(10)
            ->get();
    }
}
