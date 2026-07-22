@extends('admin.layouts.app')

@section('title', 'Generate Payroll')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-cog me-2"></i>Generate Payroll</h4>
    <a href="{{ route('admin.payroll.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form action="{{ route('admin.payroll.generate') }}" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Month <span class="text-danger">*</span></label>
                    <select name="month" class="form-select" required>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Year <span class="text-danger">*</span></label>
                    <select name="year" class="form-select" required>
                        @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i>
                Payroll will be generated for all active employees. Existing payroll for the selected period will be skipped.
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.payroll.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary" onclick="return confirm('Generate payroll for all active employees?')"><i class="fas fa-cog me-1"></i>Generate Payroll</button>
            </div>
        </form>
    </div>
</div>
@endsection
