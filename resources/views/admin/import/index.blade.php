@extends('admin.layouts.app')
@section('title', 'Import Data')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-upload"></i> Import Data</h1>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Upload File</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.import.process') }}" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Module</label>
                        <select name="module" class="form-select" required>
                            <option value="bookings">Bookings</option>
                            <option value="guests">Guests</option>
                            <option value="rooms">Rooms</option>
                            <option value="employees">Employees</option>
                            <option value="inventory">Inventory</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Format</label>
                        <select name="format" class="form-select" id="importFormat" required>
                            <option value="csv">CSV (.csv)</option>
                            <option value="excel">Excel (.xlsx, .xls)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select File</label>
                        <input type="file" name="file" class="form-control" id="importFile" accept=".csv,.xlsx,.xls" required>
                        <small class="text-muted">Maximum file size: 10MB</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="previewImport()"><i class="bi bi-eye"></i> Preview</button>
                        <button type="submit" class="btn btn-success" id="importBtn" disabled><i class="bi bi-upload"></i> Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Preview Data</h5></div>
            <div class="card-body">
                @if(!empty($data) && count($data) > 0)
                    <div class="mb-2">
                        <span class="badge bg-info">{{ $total }} records to import</span>
                        <span class="badge bg-secondary">Module: {{ $module }}</span>
                    </div>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>#</th>
                                    @foreach($headers as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        @foreach($headers as $header)
                                            <td>{{ $row[$header] ?? '' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <form method="POST" action="{{ route('admin.import.commit') }}">
                        @csrf
                        <button type="submit" class="btn btn-success mt-3"><i class="bi bi-check-lg"></i> Commit Import</button>
                    </form>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="mt-3 text-muted">Upload a file and click Preview to see data before importing.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .sticky-top { top: 0; z-index: 10; }
</style>
@endpush

@push('scripts')
<script>
function previewImport() {
    const file = document.getElementById('importFile').files[0];
    if (!file) { alert('Please select a file first.'); return; }
    const formData = new FormData();
    formData.append('file', file);
    formData.append('module', document.querySelector('[name=module]').value);
    formData.append('format', document.getElementById('importFormat').value);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("admin.import.preview") }}', {
        method: 'POST', body: formData
    }).then(r => r.text()).then(html => {
        document.open(); document.write(html); document.close();
    }).catch(err => alert('Preview failed: ' + err));
}
</script>
@endpush
@endsection
