<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function index()
    {
        return view('admin.export.index');
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'module'     => 'required|string|in:bookings,guests,rooms,revenue,employees,inventory,housekeeping',
            'format'     => 'required|in:csv,excel,pdf',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $module    = $validated['module'];
        $format    = $validated['format'];
        $startDate = $validated['start_date'] ?? now()->startOfMonth()->toDateString();
        $endDate   = $validated['end_date'] ?? now()->toDateString();

        // TODO: Generate export based on module and format

        $filename = "{$module}_export_{$startDate}_to_{$endDate}.{$format}";

        if ($format === 'csv') {
            return $this->exportCsv($module, $startDate, $endDate, $filename);
        }

        if ($format === 'excel') {
            return $this->exportExcel($module, $startDate, $endDate, $filename);
        }

        if ($format === 'pdf') {
            return $this->exportPdf($module, $startDate, $endDate, $filename);
        }

        return back()->with('error', 'Unsupported export format.');
    }

    public function printView(Request $request)
    {
        $request->validate([
            'module'     => 'required|string',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $module    = $request->input('module');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        return view('admin.export.print', compact('module', 'startDate', 'endDate'));
    }

    private function exportCsv($module, $startDate, $endDate, $filename)
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($module, $startDate, $endDate) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Module', $module]);
            fputcsv($handle, ['Date Range', "{$startDate} to {$endDate}"]);
            fputcsv($handle, ['Generated At', now()->toDateTimeString()]);
            fputcsv($handle, []);
            // TODO: Add actual data rows
            fputcsv($handle, ['Data rows will be populated here.']);
            fclose($handle);
        }, 200, $headers);
    }

    private function exportExcel($module, $startDate, $endDate, $filename)
    {
        // TODO: Generate Excel file using PhpSpreadsheet or similar
        return redirect()->back()->with('success', "Excel export '{$filename}' downloaded.");
    }

    private function exportPdf($module, $startDate, $endDate, $filename)
    {
        // TODO: Generate PDF using dompdf or similar
        return redirect()->back()->with('success', "PDF export '{$filename}' downloaded.");
    }
}
