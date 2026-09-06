<?php

namespace App\Services;

use App\Interfaces\PaymentRepositoryInterface;
use App\Interfaces\PaymentServiceInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService implements PaymentServiceInterface
{
    protected $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getPaginatedPayments(array $filters = [], int $perPage = 15)
    {
        return $this->paymentRepository->getAllPaginated($filters, $perPage);
    }

    public function getPaymentById(string $id)
    {
        return $this->paymentRepository->getById($id);
    }

    public function createPayment(array $data)
    {
        DB::beginTransaction();
        try {
            // Further business logic validation could go here
            $payment = $this->paymentRepository->create($data);
            
            // Optionally, update TherapySession status to 'Selesai' if it was pending
            $therapySession = \App\Models\TherapySession::find($payment->therapy_session_id);
            if ($therapySession && $payment->status === 'Lunas') {
                // If there's a status field to update in therapy_sessions
                // $therapySession->update(['status' => 'Completed']);
            }

            DB::commit();
            return $payment;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating payment: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updatePayment(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $payment = $this->paymentRepository->update($id, $data);
            DB::commit();
            return $payment;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating payment: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deletePayment(string $id)
    {
        DB::beginTransaction();
        try {
            $result = $this->paymentRepository->delete($id);
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting payment: ' . $e->getMessage());
            throw $e;
        }
    }

    public function exportCsv(array $filters = [])
    {
        $payments = $this->paymentRepository->getAllForExport($filters);
        
        $csvData = [];
        $headers = ['Invoice', 'Tanggal', 'Pasien', 'Fisioterapis', 'Metode Pembayaran', 'Status', 'Total'];

        foreach ($payments as $payment) {
            $csvData[] = [
                $payment->invoice_number,
                $payment->payment_date->format('Y-m-d H:i'),
                $payment->patient->name ?? '-',
                $payment->physiotherapist->name ?? '-',
                $payment->payment_method,
                $payment->status,
                $payment->total,
            ];
        }

        return [
            'headers' => $headers,
            'data' => $csvData
        ];
    }

    public function getAllForExport(array $filters = [])
    {
        return $this->paymentRepository->getAllForExport($filters);
    }

    public function generateInvoicePdf(string $id)
    {
        $payment = $this->paymentRepository->getById($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', ['payment' => $payment]);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    public function generateReceiptPdf(string $id)
    {
        $payment = $this->paymentRepository->getById($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', ['payment' => $payment]);
        // Set paper size for 58mm thermal printer. Width 164.4pt, Height auto (we set 800pt as default long receipt)
        $pdf->setPaper([0, 0, 164.4, 800], 'portrait');
        
        return $pdf;
    }
}
