<?php

namespace App\Services;

class CsvExportService
{
    /**
     * Download data as a CSV file.
     *
     * @param string $filename
     * @param array $headers
     * @param iterable|array $data
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(string $filename, array $headers, $data)
    {
        $callback = function () use ($headers, $data) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 Excel support
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Write headers
            fputcsv($file, $headers);
            
            // Write rows
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
