@extends('layouts.app')

@section('title', __('messages.edit_category'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sections') }}">{{ __('messages.sections') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('general-management.home') }}">{{ __('messages.general_management') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('general-management.medical-file-categories.index') }}">{{ __('messages.medical_file_categories') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.edit_category') }}</li>
@endsection

@section('skip-dashboard-analytics')
@endsection

@section('content')
<style>
    .form-card {
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }

    .form-card .card-header {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    .form-card .card-header h5 {
        color: #212529;
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .form-label {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control, .form-select, textarea {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 0.5rem 0.75rem;
        font-size: 0.95rem;
    }

    .form-control:focus, .form-select:focus, textarea:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn {
        border-radius: 4px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
    }

    .text-danger {
        color: #dc3545 !important;
        font-weight: 600;
    }

    .invalid-feedback {
        font-weight: 500;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('messages.edit_category') }} - {{ $medicalFileCategory->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('general-management.medical-file-categories.update', $medicalFileCategory) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('messages.category_name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $medicalFileCategory->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('messages.category_description') }}</label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $medicalFileCategory->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('general-management.medical-file-categories.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>{{ __('messages.back') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>{{ __('messages.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
