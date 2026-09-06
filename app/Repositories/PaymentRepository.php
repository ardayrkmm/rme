<?php

namespace App\Repositories;

use App\Interfaces\PaymentRepositoryInterface;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Payment::with(['patient', 'physiotherapist', 'paymentDetails.serviceMaster']);

        if (isset($filters['search']) && $filters['search']) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('invoice_number', 'like', "%{$searchTerm}%")
                  ->orWhereHas('patient', function ($q2) use ($searchTerm) {
                      $q2->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('physiotherapist', function ($q3) use ($searchTerm) {
                      $q3->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_method']) && $filters['payment_method']) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (isset($filters['start_date']) && $filters['start_date']) {
            $query->whereDate('payment_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date']) && $filters['end_date']) {
            $query->whereDate('payment_date', '<=', $filters['end_date']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getAllForExport(array $filters = [])
    {
        $query = Payment::with(['patient', 'physiotherapist', 'paymentDetails.serviceMaster']);
        
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_method']) && $filters['payment_method']) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (isset($filters['start_date']) && $filters['start_date']) {
            $query->whereDate('payment_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date']) && $filters['end_date']) {
            $query->whereDate('payment_date', '<=', $filters['end_date']);
        }

        return $query->latest()->get();
    }

    public function getById(string $id): ?Payment
    {
        return Payment::with(['patient', 'physiotherapist', 'paymentDetails.serviceMaster'])->findOrFail($id);
    }

    public function create(array $data): Payment
    {
        $paymentData = collect($data)->except('details')->toArray();
        $detailsData = collect($data['details']);
        
        // Auto-generate invoice number if not provided
        if (!isset($paymentData['invoice_number'])) {
            $date = date('Ymd');
            $latest = Payment::whereDate('created_at', date('Y-m-d'))->count() + 1;
            $paymentData['invoice_number'] = "INV-{$date}-" . str_pad($latest, 4, '0', STR_PAD_LEFT);
        }
        
        // Ensure totals are calculated correctly
        $subtotal = 0;
        foreach ($detailsData as $detail) {
            $subtotal += ($detail['price'] * $detail['quantity']);
        }
        
        $paymentData['subtotal'] = $subtotal;
        $discount = $paymentData['discount'] ?? 0;
        $tax = $paymentData['tax'] ?? 0;
        $paymentData['total'] = $subtotal - $discount + $tax;

        $payment = Payment::create($paymentData);

        foreach ($detailsData as $detail) {
            PaymentDetail::create([
                'payment_id' => $payment->id,
                'service_master_id' => $detail['service_master_id'],
                'quantity' => $detail['quantity'],
                'price' => $detail['price'],
                'subtotal' => $detail['price'] * $detail['quantity'],
            ]);
        }

        return $payment;
    }

    public function update(string $id, array $data): Payment
    {
        $payment = Payment::findOrFail($id);
        $payment->update($data);
        return $payment;
    }

    public function delete(string $id): bool
    {
        $payment = Payment::findOrFail($id);
        // Details are cascade deleted via foreign key setup
        return $payment->delete();
    }
}
