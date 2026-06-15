<?php

namespace App\Http\Controllers\Api\V1\Operations;

use App\Http\Controllers\Controller;
use App\Models\Operations\Invoice;
use App\Services\Operations\InvoiceService;
use App\Http\Requests\Operations\StoreInvoiceRequest;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $service) {}

    public function index(): JsonResponse
    {
        $orgId = auth()->user()->organizations()->first()->id;
        $invoices = $this->service->getInvoicesByOrganization($orgId);
        return response()->json([
            'success' => true,
            'data' => $invoices,
        ]);
    }

    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $invoice = $this->service->createInvoice($data);
        
        return response()->json([
            'success' => true,
            'data' => $invoice,
            'message' => 'Invoice created successfully',
        ], 201);
    }

    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $invoice->load(['client', 'company']),
        ]);
    }

    public function update(StoreInvoiceRequest $request, Invoice $invoice): JsonResponse
    {
        $updated = $this->service->updateInvoice($invoice, $request->validated());
        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'Invoice updated successfully',
        ]);
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        $invoice->update(['payment_status' => 'cancelled']);
        return response()->json([
            'success' => true,
            'message' => 'Invoice cancelled successfully',
        ]);
    }
}
