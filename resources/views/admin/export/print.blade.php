<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print - {{ ucfirst($module) }} Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
            .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; }
        }
        @media screen {
            .print-header { display: none; }
        }
        @media print {
            .print-header { display: block; }
        }
    </style>
</head>
<body>
    <div class="print-header text-center mb-4">
        <h3>{{ config('app.name', 'Hotel Management') }}</h3>
        <p class="text-muted">{{ ucfirst($module) }} Report | {{ $startDate }} to $endDate }}</p>
        <hr>
    </div>

    <div class="no-print mb-3 text-center">
        <button class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
        <button class="btn btn-outline-secondary" onclick="window.close()">Close</button>
    </div>

    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-4"><strong>Module:</strong> {{ ucfirst($module) }}</div>
            <div class="col-4"><strong>From:</strong> {{ $startDate }}</div>
            <div class="col-4"><strong>To:</strong> {{ $endDate }}</div>
        </div>
        <hr>
        <div class="card">
            <div class="card-body">
                <p class="text-muted text-center">Report data will be populated here based on the selected module.</p>
            </div>
        </div>
    </div>
</body>
</html>
