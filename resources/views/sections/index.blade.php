@extends('layouts.auth')

@section('title', __('messages.sections'))

@section('content')
<div class="text-center mb-4">
    <h2 class="fw-bold mb-2">{{ __('messages.available_sections') }}</h2>
    <p class="text-muted">{{ __('messages.select_section_to_continue') }}</p>
</div>

<div class="row g-3 justify-content-center">
    {{-- General Management Section Card - Only for admin --}}
    @hasrole('admin')
    <div class="col-md-12">
        <div class="card section-card border-0 shadow-lg h-100 hover-card">
            <div class="card-body text-center p-4">
                <div class="mb-3 icon-wrapper">
                    <div class="avtar avtar-md bg-gradient-primary mx-auto icon-pulse">
                        <i class="ti ti-building-community text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <h5 class="card-title mb-2 fw-bold">{{ __('messages.general_management') }}</h5>
                <p class="card-text text-muted mb-3 small">
                    {{ __('messages.general_management_description') }}
                </p>
                <a href="{{ route('general-management.home') }}" class="btn btn-primary w-100 rounded-pill shadow-sm">
                    <i class="ti ti-arrow-right me-2"></i>{{ __('messages.enter_section') }}
                </a>
            </div>
            <div class="card-decoration"></div>
        </div>
    </div>
    @endhasrole

    {{-- Reception Section Card - For receptionist and admin --}}
    @hasanyrole('receptionist|admin')
    <div class="col-md-12">
        <div class="card section-card border-0 shadow-lg h-100 hover-card">
            <div class="card-body text-center p-4">
                <div class="mb-3 icon-wrapper">
                    <div class="avtar avtar-md bg-gradient-success mx-auto icon-pulse">
                        <i class="ti ti-user text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <h5 class="card-title mb-2 fw-bold">{{ __('messages.reception') }}</h5>
                <p class="card-text text-muted mb-3 small">
                    {{ __('messages.reception_welcome_text') }}
                </p>
                <a href="{{ route('reception.home') }}" class="btn btn-success w-100 rounded-pill shadow-sm">
                    <i class="ti ti-arrow-right me-2"></i>{{ __('messages.enter_section') }}
                </a>
            </div>
            <div class="card-decoration"></div>
        </div>
    </div>
    @endhasanyrole
</div>

<style>
/* Section Cards */
.section-card {
    position: relative;
    overflow: hidden;
    border-radius: 20px !important;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* Hover Effects */
.hover-card {
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 1.5rem 3rem rgba(44, 148, 184, 0.25) !important;
}

.hover-card:hover .icon-pulse {
    animation: pulse 0.6s ease-in-out;
}

/* Locked Card */
.locked-card {
    opacity: 0.65;
    cursor: not-allowed;
}

/* Icon Wrapper */
.icon-wrapper {
    position: relative;
}

/* Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #2c94b8 0%, #39b8d7 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

/* Avatar Size */
.avtar-md {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
}

/* Card Decoration */
.card-decoration {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #2c94b8 0%, #39b8d7 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.hover-card:hover .card-decoration {
    opacity: 1;
}

/* Button Enhancements */
.btn.rounded-pill {
    padding: 10px 24px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-primary.rounded-pill:hover {
    background: linear-gradient(135deg, #2382a0 0%, #2c94b8 100%) !important;
    transform: scale(1.05);
}

.btn-success.rounded-pill:hover {
    background: linear-gradient(135deg, #218838 0%, #28a745 100%) !important;
    transform: scale(1.05);
}

/* Pulse Animation */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

/* Responsive Title */
@media (max-width: 768px) {
    .section-card .card-title {
        font-size: 1.1rem;
    }

    .avtar-md {
        width: 60px;
        height: 60px;
    }

    .avtar-md i {
        font-size: 1.5rem !important;
    }
}

/* Card Body Padding on Mobile */
@media (max-width: 576px) {
    .section-card .card-body {
        padding: 2rem !important;
    }
}

/* Stagger Animation on Load */
.row > div:nth-child(1) .section-card {
    animation: slideInUp 0.6s ease-out;
}

.row > div:nth-child(2) .section-card {
    animation: slideInUp 0.6s ease-out 0.1s backwards;
}

.row > div:nth-child(3) .section-card {
    animation: slideInUp 0.6s ease-out 0.2s backwards;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Text Enhancements */
.section-card .card-text {
    line-height: 1.6;
    font-size: 0.95rem;
}

.section-card .card-title {
    color: #2c3e50;
}
</style>
@endsection
