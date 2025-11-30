@extends('layouts.app')

@section('title', __('messages.edit_medical_file'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sections') }}">{{ __('messages.sections') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reception.home') }}">{{ __('messages.reception') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reception.medical-files.index') }}">{{ __('messages.medical_files') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.edit_medical_file') }}</li>
@endsection

@section('skip-dashboard-analytics')
@endsection

@section('content')
<style>
    .wizard-card {
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }

    .wizard-card .card-header {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    .wizard-card .card-header h5 {
        color: #212529;
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .nav-pills {
        background: #ffffff;
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin-bottom: 1.5rem;
    }

    .nav-pills .nav-link {
        background-color: #f8f9fa;
        color: #6c757d;
        cursor: default;
        border-radius: 4px;
        padding: 0.75rem 1rem;
        font-weight: 500;
        border: 1px solid #dee2e6;
    }

    .nav-pills .nav-link.active {
        background: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    .nav-pills .nav-link.completed {
        background: #198754;
        color: white;
        border-color: #198754;
    }

    .step-content {
        padding: 1.5rem;
        background: white;
    }

    .step-content h5 {
        color: #212529;
        font-weight: 600;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #dee2e6;
        font-size: 1.1rem;
    }

    .step-content h5 i {
        color: #6c757d;
        margin-right: 0.5rem;
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

    .row.g-3 {
        margin-bottom: 1rem;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card wizard-card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('messages.edit_medical_file') }} - {{ $medicalFile->file_number }}</h5>
            </div>
            <div class="card-body">
                <!-- Progress Steps -->
                <div class="mb-4">
                    <ul class="nav nav-pills nav-justified" id="stepIndicator">
                        <li class="nav-item">
                            <a class="nav-link active" data-step="1">
                                <i class="ti ti-file me-1"></i>
                                <span class="d-none d-md-inline">{{ __('messages.file_info') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-step="2">
                                <i class="ti ti-user me-1"></i>
                                <span class="d-none d-md-inline">{{ __('messages.husband_information') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-step="3">
                                <i class="ti ti-user me-1"></i>
                                <span class="d-none d-md-inline">{{ __('messages.wife_information') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <form action="{{ route('reception.medical-files.update', $medicalFile) }}" method="POST" id="medicalFileForm">
                    @csrf
                    @method('PUT')

                    <!-- Step 1: File Information -->
                    <div class="step-content" data-step="1">
                        <h5 class="mb-3"><i class="ti ti-file me-2"></i>{{ __('messages.file_info') }}</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="file_number" class="form-label">{{ __('messages.file_number') }} <span class="text-danger">*</span></label>
                                <input type="text" name="file_number" id="file_number"
                                    class="form-control @error('file_number') is-invalid @enderror"
                                    value="{{ old('file_number', $medicalFile->file_number) }}" required>
                                @error('file_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="registration_date" class="form-label">{{ __('messages.registration_date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="registration_date" id="registration_date"
                                    class="form-control @error('registration_date') is-invalid @enderror"
                                    value="{{ old('registration_date', $medicalFile->registration_date->format('Y-m-d')) }}" required>
                                @error('registration_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="category_id" class="form-label">{{ __('messages.category') }}</label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">{{ __('messages.select') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $medicalFile->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="region" class="form-label">{{ __('messages.region') }}</label>
                                <input type="text" name="region" id="region"
                                    class="form-control @error('region') is-invalid @enderror"
                                    value="{{ old('region', $medicalFile->region) }}">
                                @error('region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="diagnosis" class="form-label">{{ __('messages.diagnosis') }}</label>
                                <textarea name="diagnosis" id="diagnosis" rows="3"
                                    class="form-control @error('diagnosis') is-invalid @enderror">{{ old('diagnosis', $medicalFile->diagnosis) }}</textarea>
                                @error('diagnosis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Husband Information -->
                    <div class="step-content d-none" data-step="2">
                        <h5 class="mb-3"><i class="ti ti-user me-2"></i>{{ __('messages.husband_information') }}</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="husband_name" class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="husband_name" id="husband_name"
                                    class="form-control @error('husband_name') is-invalid @enderror"
                                    value="{{ old('husband_name', $medicalFile->husband?->name) }}" required>
                                @error('husband_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="husband_national_id" class="form-label">{{ __('messages.national_id') }} <span class="text-danger">*</span></label>
                                <input type="text" name="husband_national_id" id="husband_national_id"
                                    class="form-control @error('husband_national_id') is-invalid @enderror"
                                    value="{{ old('husband_national_id', $medicalFile->husband?->national_id) }}" required>
                                @error('husband_national_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="husband_registry_number" class="form-label">{{ __('messages.registry_number') }} <span class="text-danger">*</span></label>
                                <input type="text" name="husband_registry_number" id="husband_registry_number"
                                    class="form-control @error('husband_registry_number') is-invalid @enderror"
                                    value="{{ old('husband_registry_number', $medicalFile->husband?->registry_number) }}" required>
                                @error('husband_registry_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="husband_dob" class="form-label">{{ __('messages.date_of_birth') }} <span class="text-danger">*</span></label>
                                <input type="date" name="husband_dob" id="husband_dob"
                                    class="form-control @error('husband_dob') is-invalid @enderror"
                                    value="{{ old('husband_dob', $medicalFile->husband?->dob->format('Y-m-d')) }}" required>
                                @error('husband_dob')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Wife Information -->
                    <div class="step-content d-none" data-step="3">
                        <h5 class="mb-3"><i class="ti ti-user me-2"></i>{{ __('messages.wife_information') }}</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="wife_name" class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="wife_name" id="wife_name"
                                    class="form-control @error('wife_name') is-invalid @enderror"
                                    value="{{ old('wife_name', $medicalFile->wife?->name) }}" required>
                                @error('wife_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="wife_national_id" class="form-label">{{ __('messages.national_id') }} <span class="text-danger">*</span></label>
                                <input type="text" name="wife_national_id" id="wife_national_id"
                                    class="form-control @error('wife_national_id') is-invalid @enderror"
                                    value="{{ old('wife_national_id', $medicalFile->wife?->national_id) }}" required>
                                @error('wife_national_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="wife_registry_number" class="form-label">{{ __('messages.registry_number') }} <span class="text-danger">*</span></label>
                                <input type="text" name="wife_registry_number" id="wife_registry_number"
                                    class="form-control @error('wife_registry_number') is-invalid @enderror"
                                    value="{{ old('wife_registry_number', $medicalFile->wife?->registry_number) }}" required>
                                @error('wife_registry_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="wife_dob" class="form-label">{{ __('messages.date_of_birth') }} <span class="text-danger">*</span></label>
                                <input type="date" name="wife_dob" id="wife_dob"
                                    class="form-control @error('wife_dob') is-invalid @enderror"
                                    value="{{ old('wife_dob', $medicalFile->wife?->dob->format('Y-m-d')) }}" required>
                                @error('wife_dob')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                            <i class="ti ti-arrow-left me-1"></i>{{ __('messages.previous') }}
                        </button>
                        <a href="{{ route('reception.medical-files.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-x me-1"></i>{{ __('messages.back') }}
                        </a>
                        <div>
                            <button type="button" class="btn btn-primary" id="nextBtn">
                                {{ __('messages.next') }}<i class="ti ti-arrow-right ms-1"></i>
                            </button>
                            <button type="submit" class="btn btn-success d-none" id="submitBtn">
                                <i class="ti ti-check me-1"></i>{{ __('messages.update') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.nav-pills .nav-link {
    background-color: #f8f9fa;
    color: #6c757d;
    cursor: default;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #2c94b8 0%, #39b8d7 100%);
    color: white;
}

.nav-pills .nav-link.completed {
    background-color: #28a745;
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;

    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');

    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(el => {
            el.classList.add('d-none');
        });

        // Show current step
        document.querySelector(`.step-content[data-step="${step}"]`).classList.remove('d-none');

        // Update step indicators
        document.querySelectorAll('#stepIndicator .nav-link').forEach((el, index) => {
            el.classList.remove('active', 'completed');
            if (index + 1 < step) {
                el.classList.add('completed');
            } else if (index + 1 === step) {
                el.classList.add('active');
            }
        });

        // Update buttons
        prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
        nextBtn.style.display = step === totalSteps ? 'none' : 'inline-block';
        submitBtn.classList.toggle('d-none', step !== totalSteps);
    }

    nextBtn.addEventListener('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    showStep(currentStep);
});
</script>
@endsection
