<?php

namespace App\Http\Controllers\Api\V1\Operations;

use App\Http\Controllers\Controller;
use App\Models\Operations\Company;
use App\Services\Operations\CompanyService;
use App\Http\Requests\Operations\StoreCompanyRequest;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    public function __construct(private CompanyService $service) {}

    public function index(): JsonResponse
    {
        $orgId = auth()->user()->organizations()->first()->id;
        $companies = $this->service->getCompaniesByOrganization($orgId);
        return response()->json([
            'success' => true,
            'data' => $companies,
        ]);
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $company = $this->service->createCompany($data);
        
        return response()->json([
            'success' => true,
            'data' => $company,
            'message' => 'Company created successfully',
        ], 201);
    }

    public function show(Company $company): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $company->load(['accountManager', 'clients', 'invoices', 'assets']),
            'stats' => $this->service->getCompanyStats($company),
        ]);
    }

    public function update(StoreCompanyRequest $request, Company $company): JsonResponse
    {
        $updated = $this->service->updateCompany($company, $request->validated());
        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'Company updated successfully',
        ]);
    }

    public function destroy(Company $company): JsonResponse
    {
        $this->service->archiveCompany($company);
        return response()->json([
            'success' => true,
            'message' => 'Company archived successfully',
        ]);
    }
}
