@extends('layouts.app')

@section('title', __('messages.medical_file_categories'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sections') }}">{{ __('messages.sections') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('general-management.home') }}">{{ __('messages.general_management') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.medical_file_categories') }}</li>
@endsection

@section('skip-dashboard-analytics')
@endsection

@section('content')
<style>
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

    .badge-category {
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
</style>

<div class="row">
    <div class="col-12">
        <div class="card main-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">{{ __('messages.medical_file_categories') }}</h5>
                    <div class="action-buttons">
                        <a href="{{ route('general-management.medical-file-categories.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ __('messages.add_category') }}
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
                                <th>{{ __('messages.category_name') }}</th>
                                <th>{{ __('messages.category_description') }}</th>
                                <th>{{ __('messages.files_count') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td><span class="badge bg-light text-dark">{{ $loop->iteration }}</span></td>
                                <td><span class="badge-category">{{ $category->name }}</span></td>
                                <td>{{ $category->description ?? '-' }}</td>
                                <td><span class="badge bg-info">{{ $category->medical_files_count }}</span></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('general-management.medical-file-categories.edit', $category) }}" class="btn btn-sm btn-warning" title="{{ __('messages.edit_category') }}">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('general-management.medical-file-categories.destroy', $category) }}" method="POST" class="d-inline">
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
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="ti ti-folder-off"></i>
                                        <p>{{ __('messages.no_categories_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
