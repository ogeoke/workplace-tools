<?php

namespace App\Http\Controllers\Api\V1\Operations;

use App\Http\Controllers\Controller;
use App\Models\Operations\Client;
use App\Services\Operations\ClientService;
use App\Http\Requests\Operations\StoreClientRequest;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    public function __construct(private ClientService $service) {}

    public function index(): JsonResponse
    {
        $orgId = auth()->user()->organizations()->first()->id;
        $clients = $this->service->getClientsByOrganization($orgId);
        return response()->json([
            'success' => true,
            'data' => $clients,
        ]);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $client = $this->service->createClient($data);
        
        return response()->json([
            'success' => true,
            'data' => $client,
            'message' => 'Client created successfully',
        ], 201);
    }

    public function show(Client $client): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $client->load(['company', 'invoices', 'followUps']),
        ]);
    }

    public function update(StoreClientRequest $request, Client $client): JsonResponse
    {
        $updated = $this->service->updateClient($client, $request->validated());
        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'Client updated successfully',
        ]);
    }

    public function destroy(Client $client): JsonResponse
    {
        $client->delete();
        return response()->json([
            'success' => true,
            'message' => 'Client deleted successfully',
        ]);
    }
}
