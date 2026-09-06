<?php

namespace App\Interfaces;

interface PaymentServiceInterface
{
    public function getPaginatedPayments(array $filters = [], int $perPage = 15);
    public function getPaymentById(string $id);
    public function createPayment(array $data);
    public function updatePayment(string $id, array $data);
    public function deletePayment(string $id);
    public function exportCsv(array $filters = []);
    public function generateInvoicePdf(string $id);
    public function generateReceiptPdf(string $id);
    public function getAllForExport(array $filters = []);
}
