<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Interfaces\PaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'status', 'payment_method', 'start_date', 'end_date']);
        $perPage = min(100, (int) $request->input('per_page', 15));

        $payments = $this->paymentService->getPaginatedPayments($filters, $perPage);

        return $this->successResponse(
            PaymentResource::collection($payments)->response()->getData(true),
            'Data pembayaran berhasil diambil'
        );
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        try {
            $payment = $this->paymentService->createPayment($request->validated());
            $payment->load(['patient', 'physiotherapist', 'paymentDetails.serviceMaster']);
            return $this->successResponse(new PaymentResource($payment), 'Pembayaran berhasil dibuat', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Gagal membuat pembayaran: ' . $e->getMessage(), 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $payment = $this->paymentService->getPaymentById($id);
            return $this->successResponse(new PaymentResource($payment), 'Detail pembayaran berhasil diambil');
        } catch (Exception $e) {
            return $this->errorResponse('Pembayaran tidak ditemukan', 404);
        }
    }

    public function update(UpdatePaymentRequest $request, string $id): JsonResponse
    {
        try {
            $payment = $this->paymentService->updatePayment($id, $request->validated());
            $payment->load(['patient', 'physiotherapist', 'paymentDetails.serviceMaster']);
            return $this->successResponse(new PaymentResource($payment), 'Status pembayaran berhasil diupdate');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal update pembayaran: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        if (!in_array(auth()->user()->role->value, ['admin', 'owner'])) {
            return $this->errorResponse('Unauthorized action.', 403);
        }

        try {
            $this->paymentService->deletePayment($id);
            return $this->successResponse(null, 'Pembayaran berhasil dihapus');
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal menghapus pembayaran: ' . $e->getMessage(), 500);
        }
    }

    public function exportCsv(Request $request, \App\Services\CsvExportService $csvService): StreamedResponse
    {
        $filters = $request->only(['status', 'payment_method', 'start_date', 'end_date']);
        $exportData = $this->paymentService->exportCsv($filters);

        $filename = 'payments_export_' . date('Y-m-d') . '.csv';

        return $csvService->download($filename, $exportData['headers'], $exportData['data']);
    }

    public function exportListPdf(Request $request)
    {
        try {
            $filters = $request->only(['status', 'payment_method', 'start_date', 'end_date']);
            $payments = $this->paymentService->getAllForExport($filters);
            
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.payments_report', [
                'payments' => $payments,
                'start_date' => $filters['start_date'] ?? null,
                'end_date' => $filters['end_date'] ?? null,
                'status' => $filters['status'] ?? null,
            ]);
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download('laporan_pembayaran_' . date('Ymd') . '.pdf');
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal ekspor PDF: ' . $e->getMessage()], 500);
        }
    }

    public function previewPdf(string $id)
    {
        try {
            $pdf = $this->paymentService->generateInvoicePdf($id);
            return $pdf->stream('Invoice_' . $id . '.pdf');
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal membuat PDF: ' . $e->getMessage()], 500);
        }
    }

    public function downloadPdf(string $id)
    {
        try {
            $payment = $this->paymentService->getPaymentById($id);
            $pdf = $this->paymentService->generateInvoicePdf($id);
            return $pdf->download('Invoice_' . $payment->invoice_number . '.pdf');
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mendownload PDF: ' . $e->getMessage()], 500);
        }
    }

    public function publicDownloadPdf(string $id)
    {
        try {
            $payment = $this->paymentService->getPaymentById($id);
            $pdf = $this->paymentService->generateInvoicePdf($id);
            return $pdf->download('Invoice_' . $payment->invoice_number . '.pdf');
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mendownload PDF: ' . $e->getMessage()], 500);
        }
    }

    public function previewReceipt(string $id)
    {
        try {
            $pdf = $this->paymentService->generateReceiptPdf($id);
            return $pdf->stream('Struk_' . $id . '.pdf');
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal membuat struk: ' . $e->getMessage()], 500);
        }
    }

    public function downloadReceipt(string $id)
    {
        try {
            $payment = $this->paymentService->getPaymentById($id);
            $pdf = $this->paymentService->generateReceiptPdf($id);
            return $pdf->download('Struk_' . $payment->invoice_number . '.pdf');
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mendownload struk: ' . $e->getMessage()], 500);
        }
    }

    public function shareLink(string $id): JsonResponse
    {
        try {
            // Verify payment exists
            $this->paymentService->getPaymentById($id);
            
            $url = \Illuminate\Support\Facades\URL::signedRoute('payment.invoice.download', ['id' => $id]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'share_url' => $url
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal generate link: ' . $e->getMessage()], 500);
        }
    }
}
