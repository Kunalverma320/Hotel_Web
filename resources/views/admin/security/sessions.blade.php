@extends('admin.layouts.app')
@section('title', 'Active Sessions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-laptop"></i> Active Sessions</h1>
    <a href="{{ route('admin.security.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Device / Browser</th>
                        <th>IP Address</th>
                        <th>Location</th>
                        <th>Last Activity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="bi bi-laptop"></i> Chrome on Windows</td>
                        <td class="font-monospace">192.168.1.1</td>
                        <td>New York, US</td>
                        <td>Just now</td>
                        <td><span class="badge bg-success">Current</span></td>
                        <td><span class="text-muted">This session</span></td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-phone"></i> Safari on iPhone</td>
                        <td class="font-monospace">10.0.0.5</td>
                        <td>London, UK</td>
                        <td>2 hours ago</td>
                        <td><span class="badge bg-secondary">Active</span></td>
                        <td>
                            <form method="POST" action="{{ route('admin.security.sessions.terminate', 1) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Terminate this session?')"><i class="bi bi-x-lg"></i> Terminate</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
