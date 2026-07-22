<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class ExportService
{
    public function exportCsv(Collection|array $data, string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $data = $data instanceof Collection ? $data->toArray() : $data;

        if (empty($data)) {
            return Response::stream(function () {}, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            ]);
        }

        $headers = array_keys($data[0]);

        return Response::stream(function () use ($data, $headers) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, $headers);

            foreach ($data as $row) {
                fputcsv($handle, array_values($row));
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    public function exportExcel(Collection|array $data, string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $data = $data instanceof Collection ? $data->toArray() : $data;

        return Response::stream(function () use ($data, $filename) {
            $handle = fopen('php://output', 'w');

            if (!empty($data)) {
                fputcsv($handle, array_keys($data[0]), "\t");

                foreach ($data as $row) {
                    fputcsv($handle, array_values($row), "\t");
                }
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}.xls\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ]);
    }

    public function exportPdf(Collection|array $data, string $filename, string $view): \Symfony\HttpFoundation\Response
    {
        $data = $data instanceof Collection ? $data->toArray() : $data;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, [
            'data' => $data,
            'exported_at' => now(),
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }
}
