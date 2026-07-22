@extends('admin.layouts.app')
@section('title', 'Export Data')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-download"></i> Export Data</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.export.download') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Module</label>
                    <select name="module" class="form-select" required>
                        <option value="bookings">Bookings</option>
                        <option value="guests">Guests</option>
                        <option value="rooms">Rooms</option>
                        <option value="revenue">Revenue</option>
                        <option value="employees">Employees</option>
                        <option value="inventory">Inventory</option>
                        <option value="housekeeping">Housekeeping</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Format</label>
                    <select name="format" class="form-select" required>
                        <option value="csv">CSV</option>
                        <option value="excel">Excel (XLSX)</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ now()->startOfMonth()->toDateString() }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ now()->toDateString() }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-download"></i> Export</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mt-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-4">
            <i class="bi bi-filetype-csv fs-1 text-success"></i>
            <h5 class="mt-2">CSV Export</h5>
            <p class="text-muted">Comma-separated values, compatible with all spreadsheet apps</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-4">
            <i class="bi bi-filetype-xlsx fs-1 text-primary"></i>
            <h5 class="mt-2">Excel Export</h5>
            <p class="text-muted">Native Excel format with formatting and multiple sheets</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-4">
            <i class="bi bi-filetype-pdf fs-1 text-danger"></i>
            <h5 class="mt-2">PDF Export</h5>
            <p class="text-muted">Print-ready PDF documents with charts and tables</p>
        </div>
    </div>
</div>
@endsection
