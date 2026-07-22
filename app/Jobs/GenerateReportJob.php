<?php

namespace App\Jobs;

use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateReportJob implements ShouldQueue
{
    use Queueable;

    public string $type;
    public array $parameters;
    public ?int $userId;

    public function __construct(string $type, array $parameters = [], ?int $userId = null)
    {
        $this->type = $type;
        $this->parameters = $parameters;
        $this->userId = $userId;
    }

    public function handle(ReportService $reportService): void
    {
        try {
            $data = match ($this->type) {
                'occupancy' => $reportService->occupancyReport($this->parameters),
                'revenue' => $reportService->revenueReport($this->parameters),
                'bookings' => $reportService->bookingsReport($this->parameters),
                'guests' => $reportService->guestsReport($this->parameters),
                'housekeeping' => $reportService->housekeepingReport($this->parameters),
                'inventory' => $reportService->inventoryReport($this->parameters),
                'restaurant' => $reportService->restaurantReport($this->parameters),
                default => throw new \InvalidArgumentException("Unknown report type: {$this->type}"),
            };

            $filename = 'report-' . $this->type . '-' . now()->format('Y-m-d-H-i-s') . '.pdf';
            $path = 'reports/' . $filename;

            $format = $this->parameters['format'] ?? 'pdf';

            if ($format === 'csv') {
                $csvPath = 'reports/' . str_replace('.pdf', '.csv', $filename);
                $handle = fopen(Storage::path($csvPath), 'w');

                if (!empty($data)) {
                    fputcsv($handle, array_keys((array) $data[0]));
                    foreach ($data as $row) {
                        fputcsv($handle, (array) $row);
                    }
                }
                fclose($handle);
                $outputPath = $csvPath;
            } else {
                $view = 'reports.' . $this->type;
                $pdf = Pdf::loadView($view, ['data' => $data, 'parameters' => $this->parameters]);
                Storage::put($path, $pdf->output());
                $outputPath = $path;
            }

            activity()
                ->withProperties([
                    'report_type' => $this->type,
                    'file' => $outputPath,
                    'parameters' => $this->parameters,
                ])
                ->event('report_generated')
                ->log('Report generated: ' . $this->type);
        } catch (\Exception $e) {
            Log::error('GenerateReportJob failed', [
                'type' => $this->type,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
