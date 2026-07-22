<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function dailyReport(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->input('date', today()->toDateString());

        return view('admin.reports.daily', compact('date'));
    }

    public function monthlyReport(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year'  => 'required|integer|min:2000|max:2100',
        ]);

        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        return view('admin.reports.monthly', compact('month', 'year'));
    }

    public function yearlyReport(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $year = $request->input('year', now()->year);

        return view('admin.reports.monthly', ['month' => null, 'year' => $year]);
    }

    public function occupancyReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        return view('admin.reports.occupancy', compact('startDate', 'endDate'));
    }

    public function revenueReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        return view('admin.reports.revenue', compact('startDate', 'endDate'));
    }

    public function guestReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        return view('admin.reports.guest', compact('startDate', 'endDate'));
    }

    public function bookingReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        return view('admin.reports.booking', compact('startDate', 'endDate'));
    }

    public function employeeReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        return view('admin.reports.daily', compact('startDate', 'endDate'));
    }

    public function housekeepingReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        return view('admin.reports.daily', compact('startDate', 'endDate'));
    }

    public function restaurantReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        return view('admin.reports.daily', compact('startDate', 'endDate'));
    }

    public function laundryReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        return view('admin.reports.daily', compact('startDate', 'endDate'));
    }

    public function inventoryReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        return view('admin.reports.daily', compact('startDate', 'endDate'));
    }

    public function auditReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'user_id'    => 'nullable|integer',
            'module'     => 'nullable|string',
            'action'     => 'nullable|string',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());
        $userId    = $request->input('user_id');
        $module    = $request->input('module');
        $action    = $request->input('action');

        return view('admin.reports.daily', compact('startDate', 'endDate', 'userId', 'module', 'action'));
    }

    public function exportReport(Request $request, $type)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'format'     => 'required|in:csv,pdf',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());
        $format    = $request->input('format');

        // TODO: Generate report based on $type, $startDate, $endDate
        // Return CSV or PDF download

        $filename = "{$type}_report_{$startDate}_to_{$endDate}.{$format}";

        return response()->streamDownload(function () use ($type, $startDate, $endDate, $format) {
            echo "Report: {$type}\n";
            echo "Date Range: {$startDate} to {$endDate}\n";
            echo "Format: {$format}\n";
        }, $filename);
    }
}
