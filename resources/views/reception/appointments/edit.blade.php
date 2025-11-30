@extends('layouts.app')

@section('title', __('messages.edit_appointment'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sections') }}">{{ __('messages.sections') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reception.home') }}">{{ __('messages.reception') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reception.appointments.index') }}">{{ __('messages.appointments') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.edit_appointment') }}</li>
@endsection

@section('skip-dashboard-analytics')
@endsection

@section('content')
<style>
    .form-card {
        border: 1px solid #e8e8e8;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .form-card .card-header {
        background: white;
        border-bottom: 1px solid #e8e8e8;
        padding: 1.75rem 2rem;
    }

    .form-card .card-header h5 {
        color: #1a1a1a;
        font-weight: 600;
        margin: 0;
        font-size: 1.25rem;
    }

    .form-card .card-body {
        padding: 2.5rem;
        background: #fafafa;
    }

    .form-label {
        font-weight: 500;
        color: #2c2c2c;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #d8d8d8;
        padding: 0.65rem 1.1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: white;
    }

    .form-control:focus {
        border-color: #4a5568;
        box-shadow: 0 0 0 3px rgba(74, 85, 104, 0.08);
        background: white;
    }

    .btn {
        border-radius: 10px;
        padding: 0.65rem 1.6rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: #2d3748;
        border: 1px solid #2d3748;
        color: white;
    }

    .btn-primary:hover {
        background: #1a202c;
        border-color: #1a202c;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(45, 55, 72, 0.2);
    }

    .btn-secondary {
        background: white;
        border: 1px solid #d8d8d8;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background: #f7f7f7;
        border-color: #c8c8c8;
    }

    .search-section {
        background: white;
        padding: 1.75rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border: 1px solid #e8e8e8;
    }

    .help-text {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 0.5rem;
    }

    .loading-spinner {
        display: none;
        margin-left: 0.75rem;
    }

    .loading-spinner.show {
        display: inline-block;
    }

    /* Patient Selection Cards */
    .patient-selection {
        display: none;
        margin-top: 2rem;
    }

    .patient-selection.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .patient-card {
        border: 2px solid #e8e8e8;
        border-radius: 14px;
        padding: 2rem;
        transition: all 0.25s ease;
        cursor: pointer;
        position: relative;
        background: white;
    }

    .patient-card:hover {
        border-color: #4a5568;
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .patient-card.husband {
        border-color: #dbeafe;
    }

    .patient-card.husband.selected {
        border-color: #3b82f6;
        background: #f0f9ff;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .patient-card.wife {
        border-color: #fce7f3;
    }

    .patient-card.wife.selected {
        border-color: #ec4899;
        background: #fef1f7;
        box-shadow: 0 4px 12px rgba(236, 72, 153, 0.15);
    }

    .patient-card-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        font-size: 1.75rem;
    }

    .patient-card.husband .patient-card-icon {
        background: #3b82f6;
        color: white;
    }

    .patient-card.wife .patient-card-icon {
        background: #ec4899;
        color: white;
    }

    .patient-card-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .patient-card-name {
        font-size: 1.15rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .patient-card-id {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .patient-card .form-check-input {
        display: none;
    }

    /* Selection indicator */
    .patient-card::after {
        content: '';
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        width: 28px;
        height: 28px;
        border: 3px solid #d1d5db;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .patient-card.selected::after {
        border-width: 8px;
    }

    .patient-card.husband.selected::after {
        border-color: #3b82f6;
    }

    .patient-card.wife.selected::after {
        border-color: #ec4899;
    }

    .notes-section {
        background: white;
        padding: 1.75rem;
        border-radius: 12px;
        margin-top: 2rem;
        border: 1px solid #e8e8e8;
    }

    textarea.form-control {
        resize: none;
    }

    .selection-header {
        font-size: 1.05rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e8e8e8;
    }

    .selection-header i {
        color: #4a5568;
        margin-right: 0.5rem;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card form-card">
            <div class="card-body">
                <div class="search-section">
                    <label class="form-label">
                        <i class="ti ti-file me-1"></i>{{ __('messages.file_number') }}
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $appointment->medicalFile->file_number }}" readonly disabled>
                    </div>
                </div>

                <!-- Appointment Form -->
                <form action="{{ route('reception.appointments.update', $appointment) }}" method="POST" id="appointmentForm">
                    @csrf
                    @method('PUT')

                    <!-- Patient Selection Cards -->
                    <div class="patient-selection show" id="patientSelection">
                        <div class="selection-header">
                            <i class="ti ti-users"></i>{{ __('messages.select_at_least_one_patient') }}
                        </div>

                        <div class="row g-4">
                            <!-- Husband Card -->
                            <div class="col-md-6">
                                <div class="patient-card husband {{ $appointment->include_husband ? 'selected' : '' }}" id="husbandCard">
                                    <input class="form-check-input" type="checkbox" name="include_husband" id="include_husband" value="1" {{ $appointment->include_husband ? 'checked' : '' }}>
                                    <div class="patient-card-icon">
                                        <i class="ti ti-user"></i>
                                    </div>
                                    <div class="patient-card-title">{{ __('messages.husband') }}</div>
                                    <div class="patient-card-name" id="husbandName">{{ $appointment->medicalFile->husband?->name ?? '-' }}</div>
                                    <div class="patient-card-id">
                                        <i class="ti ti-id me-1"></i><span id="husbandId">{{ $appointment->medicalFile->husband?->national_id ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Wife Card -->
                            <div class="col-md-6">
                                <div class="patient-card wife {{ $appointment->include_wife ? 'selected' : '' }}" id="wifeCard">
                                    <input class="form-check-input" type="checkbox" name="include_wife" id="include_wife" value="1" {{ $appointment->include_wife ? 'checked' : '' }}>
                                    <div class="patient-card-icon">
                                        <i class="ti ti-user"></i>
                                    </div>
                                    <div class="patient-card-title">{{ __('messages.wife') }}</div>
                                    <div class="patient-card-name" id="wifeName">{{ $appointment->medicalFile->wife?->name ?? '-' }}</div>
                                    <div class="patient-card-id">
                                        <i class="ti ti-id me-1"></i><span id="wifeId">{{ $appointment->medicalFile->wife?->national_id ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="notes-section">
                            <label for="notes" class="form-label">
                                <i class="ti ti-note me-1"></i>{{ __('messages.notes') }}
                            </label>
                            <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') is-invalid @enderror" placeholder="{{ __('messages.notes') }}...">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('reception.appointments.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>{{ __('messages.back') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i>{{ __('messages.update') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const husbandCard = document.getElementById('husbandCard');
    const wifeCard = document.getElementById('wifeCard');
    const husbandCheckbox = document.getElementById('include_husband');
    const wifeCheckbox = document.getElementById('include_wife');

    // Card click handlers
    husbandCard.addEventListener('click', function(e) {
        if (e.target !== husbandCheckbox) {
            husbandCheckbox.checked = !husbandCheckbox.checked;
            toggleCardSelection(husbandCard, husbandCheckbox.checked);
        } else {
            toggleCardSelection(husbandCard, husbandCheckbox.checked);
        }
    });

    wifeCard.addEventListener('click', function(e) {
        if (e.target !== wifeCheckbox) {
            wifeCheckbox.checked = !wifeCheckbox.checked;
            toggleCardSelection(wifeCard, wifeCheckbox.checked);
        } else {
            toggleCardSelection(wifeCard, wifeCheckbox.checked);
        }
    });

    husbandCheckbox.addEventListener('change', function() {
        toggleCardSelection(husbandCard, this.checked);
    });

    wifeCheckbox.addEventListener('change', function() {
        toggleCardSelection(wifeCard, this.checked);
    });

    function toggleCardSelection(card, isSelected) {
        if (isSelected) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    }
});
</script>
@endsection
