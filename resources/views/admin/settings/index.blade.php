@extends('admin.layouts.app')
@section('title', 'Settings')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-gear"></i> Settings</h1>
</div>

<ul class="nav nav-tabs" id="settingsTabs" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#general">General</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tax">Tax</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#currency">Currency</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#language">Language</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#email">Email</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#sms">SMS</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#payment">Payment</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#theme">Theme</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#backup">Backup</a></li>
</ul>

<div class="tab-content card border-0 shadow-sm rounded-0 rounded-bottom p-4" id="settingsTabContent">
    <div class="tab-pane fade show active" id="general">@include('admin.settings.general')</div>
    <div class="tab-pane fade" id="tax">@include('admin.settings.tax')</div>
    <div class="tab-pane fade" id="currency">@include('admin.settings.currency')</div>
    <div class="tab-pane fade" id="language">@include('admin.settings.language')</div>
    <div class="tab-pane fade" id="email">@include('admin.settings.email')</div>
    <div class="tab-pane fade" id="sms">@include('admin.settings.sms')</div>
    <div class="tab-pane fade" id="payment">@include('admin.settings.payment')</div>
    <div class="tab-pane fade" id="theme">@include('admin.settings.theme')</div>
    <div class="tab-pane fade" id="backup">@include('admin.settings.backup')</div>
</div>
@endsection
