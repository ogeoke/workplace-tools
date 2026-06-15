<?php

namespace App\Services\Operations;

use App\Models\Operations\Invoice;
use App\Models\Operations\Client;
use App\Models\Operations\Company;
use App\Events\Operations\InvoiceCreated;
use App\Events\Operations\InvoiceDue;
use Illuminate\Database\Eloquent\Collection;

class InvoiceService
{
    public function createInvoice(array $data): Invoice
    {
        $data['created_by'] = auth()->id();
        $invoice = Invoice::create($data);
        InvoiceCreated::dispatch($invoice);
        return $invoice->load(['client', 'company']);
    }

    public function updateInvoice(Invoice $invoice, array $data): Invoice
    {
        $invoice->update($data);
        return $invoice->refresh();
    }

    public function markAsPaid(Invoice $invoice): Invoice
    {
        $invoice->update([
            'payment_status' => 'paid',
            'paid_date' => now(),
        ]);
        return $invoice->refresh();
    }

    public function markAsOverdue(Invoice $invoice): Invoice
    {
        $invoice->update(['payment_status' => 'overdue']);
        return $invoice->refresh();
    }

    public function getInvoicesByOrganization(int $organizationId): Collection
    {
        return Invoice::where('organization_id', $organizationId)
            ->with(['client', 'company'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getInvoicesByStatus(int $organizationId, string $status): Collection
    {
        return Invoice::where('organization_id', $organizationId)
            ->where('payment_status', $status)
            ->orderBy('due_date', 'asc')
            ->get();
    }

    public function getPendingInvoices(int $organizationId): int
    {
        return Invoice::where('organization_id', $organizationId)
            ->whereNotIn('payment_status', ['paid', 'cancelled'])
            ->count();
    }

    public function getOverdueInvoices(int $organizationId): Collection
    {
        return Invoice::where('organization_id', $organizationId)
            ->where('due_date', '<', now())
            ->where('payment_status', '!=', 'paid')
            ->get();
    }

    public function getTotalAmount(int $organizationId, ?string $status = null)
    {
        $query = Invoice::where('organization_id', $organizationId);
        if ($status) {
            $query->where('payment_status', $status);
        }
        return $query->sum('amount');
    }
}
