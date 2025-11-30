@extends('layouts.app')

@section('title', __('messages.appointments'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sections') }}">{{ __('messages.sections') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reception.home') }}">{{ __('messages.reception') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.appointments') }}</li>
@endsection

@section('skip-dashboard-analytics')
@endsection

@section('content')
<style>
    .filter-card {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
    }

    .filter-card .card-header {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    .filter-card .card-header h5 {
        color: #495057;
        font-weight: 600;
        font-size: 1rem;
    }

    .filter-card .card-header i {
        color: #6c757d;
    }

    .form-label {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .main-card {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
    }

    .main-card .card-header {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 1.25rem 1.5rem;
    }

    .main-card .card-header h5 {
        color: #212529;
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .action-buttons .btn {
        border-radius: 4px;
        font-weight: 500;
        padding: 0.5rem 1rem;
    }

    .table {
        margin-bottom: 0;
        border: 1px solid #dee2e6;
    }

    .table thead th {
        background: #f8f9fa;
        color: #212529;
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: 2px solid #dee2e6;
        padding: 0.75rem;
        vertical-align: middle;
    }

    .table tbody tr {
        border-bottom: 1px solid #dee2e6;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table tbody td {
        padding: 0.75rem;
        vertical-align: middle;
        color: #495057;
    }

    .btn-group .btn {
        border-radius: 3px;
        margin: 0 1px;
    }

    .badge-file-number {
        background: #0d6efd;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 3px;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .empty-state {
        padding: 2rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 3rem;
        color: #adb5bd;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6c757d;
        font-size: 1rem;
        margin-top: 0.5rem;
    }

    .patient-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        margin: 0.1rem;
        border-radius: 3px;
        font-size: 0.8rem;
    }

    .patient-badge.husband {
        background: #e3f2fd;
        color: #1976d2;
    }

    .patient-badge.wife {
        background: #fce4ec;
        color: #c2185b;
    }
</style>

<div class="row">
    <div class="col-12">
        <!-- Filter Card -->
        <div class="card filter-card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-filter me-2"></i>{{ __('messages.filter') }}</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reception.appointments.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.file_number') }}</label>
                            <input type="text" name="file_number" class="form-control" value="{{ request('file_number') }}" placeholder="{{ __('messages.file_number') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.patient_name') }}</label>
                            <input type="text" name="patient_name" class="form-control" value="{{ request('patient_name') }}" placeholder="{{ __('messages.patient_name') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('messages.from_date') }}</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('messages.to_date') }}</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ti ti-search me-1"></i>{{ __('messages.search') }}
                            </button>
                            <a href="{{ route('reception.appointments.index') }}" class="btn btn-secondary">
                                <i class="ti ti-x me-1"></i>{{ __('messages.close') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card main-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">{{ __('messages.appointments') }}</h5>
                    <div class="action-buttons">
                        <a href="{{ route('reception.appointments.print-list', request()->all()) }}" class="btn btn-secondary btn-sm" target="_blank">
                            <i class="ti ti-printer me-1"></i>{{ __('messages.print_list') }}
                        </a>
                        <a href="{{ route('reception.appointments.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ __('messages.add_appointment') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.file_number') }}</th>
                                <th>{{ __('messages.patients') }}</th>
                                <th>{{ __('messages.notes') }}</th>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                            <tr>
                                <td><span class="badge bg-light text-dark">{{ $loop->iteration }}</span></td>
                                <td><span class="badge-file-number">{{ $appointment->medicalFile->file_number }}</span></td>
                                <td>
                                    @if($appointment->include_husband)
                                        <span class="patient-badge husband">
                                            <i class="ti ti-user me-1"></i>{{ $appointment->medicalFile->husband?->name }}
                                        </span>
                                    @endif
                                    @if($appointment->include_wife)
                                        <span class="patient-badge wife">
                                            <i class="ti ti-user me-1"></i>{{ $appointment->medicalFile->wife?->name }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($appointment->notes, 50) ?? '-' }}</td>
                                <td><i class="ti ti-calendar me-1 text-success"></i>{{ $appointment->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('reception.appointments.edit', $appointment) }}" class="btn btn-sm btn-primary" title="{{ __('messages.edit') }}">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form action="{{ route('reception.appointments.destroy', $appointment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.confirm_delete') }}')" title="{{ __('messages.confirm_delete') }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="ti ti-calendar-off"></i>
                                        <p>{{ __('messages.no_appointments_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $appointments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
