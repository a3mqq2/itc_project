@extends('layouts.app')

@section('title', __('messages.medical_files'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sections') }}">{{ __('messages.sections') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reception.home') }}">{{ __('messages.reception') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.medical_files') }}</li>
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

    .form-control {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 0.5rem 0.75rem;
        font-size: 0.95rem;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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

    .form-label {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
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
                <form method="GET" action="{{ route('reception.medical-files.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.file_number') }}</label>
                            <input type="text" name="file_number" class="form-control" value="{{ request('file_number') }}" placeholder="{{ __('messages.file_number') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.region') }}</label>
                            <input type="text" name="region" class="form-control" value="{{ request('region') }}" placeholder="{{ __('messages.region') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.patient_name') }}</label>
                            <input type="text" name="patient_name" class="form-control" value="{{ request('patient_name') }}" placeholder="{{ __('messages.patient_name') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.from_date') }}</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.to_date') }}</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ti ti-search me-1"></i>{{ __('messages.search') }}
                            </button>
                            <a href="{{ route('reception.medical-files.index') }}" class="btn btn-secondary">
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
                    <h5 class="mb-0">{{ __('messages.medical_files') }}</h5>
                    <div class="d-flex gap-2 flex-wrap action-buttons">
                        <!-- Export Excel -->
                        <a href="{{ route('reception.medical-files.export-excel', request()->all()) }}" class="btn btn-success btn-sm">
                            <i class="ti ti-file-spreadsheet me-1"></i>{{ __('messages.export_excel') }}
                        </a>

                        <!-- Import Excel Modal Trigger -->
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="ti ti-upload me-1"></i>{{ __('messages.import_excel') }}
                        </button>

                        <!-- Download Template -->
                        <a href="{{ route('reception.medical-files.download-template') }}" class="btn btn-secondary btn-sm">
                            <i class="ti ti-download me-1"></i>{{ __('messages.download_template') }}
                        </a>

                        <!-- Add New -->
                        <a href="{{ route('reception.medical-files.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ __('messages.add_medical_file') }}
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
                                <th>{{ __('messages.category') }}</th>
                                <th>{{ __('messages.husband') }}</th>
                                <th>{{ __('messages.wife') }}</th>
                                <th>{{ __('messages.region') }}</th>
                                <th>{{ __('messages.registration_date') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medicalFiles as $file)
                            <tr>
                                <td><span class="badge bg-light text-dark">{{ $loop->iteration }}</span></td>
                                <td><span class="badge-file-number">{{ $file->file_number }}</span></td>
                                <td><i class="ti ti-folder me-1 text-warning"></i>{{ $file->category?->name ?? '-' }}</td>
                                <td><i class="ti ti-user me-1 text-primary"></i>{{ $file->husband?->name ?? '-' }}</td>
                                <td><i class="ti ti-user me-1 text-danger"></i>{{ $file->wife?->name ?? '-' }}</td>
                                <td><i class="ti ti-map-pin me-1 text-info"></i>{{ $file->region ?? '-' }}</td>
                                <td><i class="ti ti-calendar me-1 text-success"></i>{{ $file->registration_date->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('reception.medical-files.show', $file) }}" class="btn btn-sm btn-info" title="{{ __('messages.view_file') }}">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('reception.medical-files.edit', $file) }}" class="btn btn-sm btn-warning" title="{{ __('messages.edit_medical_file') }}">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="{{ route('reception.medical-files.print', $file) }}" class="btn btn-sm btn-secondary" target="_blank" title="{{ __('messages.print') }}">
                                            <i class="ti ti-printer"></i>
                                        </a>
                                        <form action="{{ route('reception.medical-files.destroy', $file) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.confirm_delete') }}')" title="{{ __('messages.confirm_delete') }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="ti ti-file-off"></i>
                                        <p>{{ __('messages.no_medical_files_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $medicalFiles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">{{ __('messages.import_excel') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('reception.medical-files.import-excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">{{ __('messages.choose_file') }}</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">
                            {{ __('messages.download_template') }}:
                            <a href="{{ route('reception.medical-files.download-template') }}" target="_blank">
                                {{ __('messages.download_template') }}
                            </a>
                        </div>
                    </div>
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-upload me-1"></i>{{ __('messages.upload') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
