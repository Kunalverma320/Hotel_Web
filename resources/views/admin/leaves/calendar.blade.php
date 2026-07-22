@extends('admin.layouts.app')

@section('title', 'Leave Calendar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-calendar me-2"></i>Leave Calendar - {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</h4>
    <a href="{{ route('admin.leaves.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.leaves.calendar') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Month</label>
                <select name="month" class="form-select">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Year</label>
                <select name="year" class="form-select">
                    @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Show</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @php
            $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
            $daysInMonth = $startOfMonth->daysInMonth;
            $startDay = $startOfMonth->dayOfWeek;
        @endphp

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <th>{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentDay = 1;
                        $weeks = ceil(($daysInMonth + $startDay) / 7);
                    @endphp
                    @for($week = 0; $week < $weeks; $week++)
                        <tr>
                            @for($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++)
                                @php $dayIndex = $week * 7 + $dayOfWeek; @endphp
                                <td style="height:80px;vertical-align:top;" class="{{ $dayIndex < $startDay || $currentDay > $daysInMonth ? 'table-light' : '' }}">
                                    @if($dayIndex >= $startDay && $currentDay <= $daysInMonth)
                                        <strong>{{ $currentDay }}</strong>
                                        @php
                                            $dateStr = \Carbon\Carbon::create($year, $month, $currentDay)->toDateString();
                                            $dayLeaves = $leaves->filter(function($l) use ($dateStr) {
                                                return $l->start_date->toDateString() <= $dateStr && $l->end_date->toDateString() >= $dateStr;
                                            });
                                        @endphp
                                        @foreach($dayLeaves as $leave)
                                            <div class="badge bg-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'pending' ? 'warning' : 'danger') }} d-block mb-1" style="font-size:0.65rem;">
                                                {{ $leave->employee->first_name }}
                                            </div>
                                        @endforeach
                                        @php $currentDay++; @endphp
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <span class="badge bg-success me-2">Approved</span>
            <span class="badge bg-warning text-dark me-2">Pending</span>
            <span class="badge bg-danger">Rejected</span>
        </div>
    </div>
</div>
@endsection
