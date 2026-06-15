<?php

namespace App\Repositories\Implementations\Operations;

use App\Models\Operations\Invoice;
use App\Repositories\Implementations\BaseRepository;

class InvoiceRepository extends BaseRepository
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function getByOrganization(int $organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->with(['client', 'company'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByStatus(int $organizationId, string $status)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('payment_status', $status)
            ->orderBy('due_date', 'asc')
            ->get();
    }

    public function getPendingInvoices(int $organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('payment_status', '!=', 'paid')
            ->count();
    }

    public function getOverdueInvoices(int $organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('due_date', '<', now())
            ->where('payment_status', '!=', 'paid')
            ->get();
    }

    public function getTotalAmount(int $organizationId, string $status = null)
    {
        $query = $this->model->where('organization_id', $organizationId);

        if ($status) {
            $query->where('payment_status', $status);
        }

        return $query->sum('amount');
    }
}
