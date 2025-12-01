@extends('layouts.app')

@section('title', __('messages.reception'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sections') }}">{{ __('messages.sections') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.reception') }}</li>
@endsection

@section('skip-dashboard-analytics')
@endsection

@section('content')
<style>
    .stats-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    .chart-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .chart-card .card-header {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    .chart-card .card-header h5 {
        color: #495057;
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
    }

    .chart-card .card-body {
        padding: 1.5rem;
    }

    .chart-container {
        position: relative;
        height: 320px;
        max-height: 320px;
    }

    .chart-container-small {
        position: relative;
        height: 200px;
        max-height: 200px;
    }

    .chart-container-large {
        position: relative;
        height: 400px;
        max-height: 400px;
    }

    .quick-search {
        background: white;
        border: 2px solid #2c94b8;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .quick-search input {
        border: 1px solid #d8d8d8;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }

    .quick-search input:focus {
        border-color: #2c94b8;
        box-shadow: 0 0 0 3px rgba(44, 148, 184, 0.1);
    }

    .quick-actions .btn {
        padding: 1rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .quick-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .today-appointments-table {
        font-size: 0.9rem;
    }

    .today-appointments-table th {
        background: #2c94b8;
        color: white;
        font-weight: 600;
        padding: 0.75rem;
    }

    .today-appointments-table td {
        padding: 0.75rem;
        vertical-align: middle;
    }

    .patient-badge {
        display: inline-block;
        padding: 4px 10px;
        margin: 2px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .patient-badge.husband {
        background: #e3f2fd;
        color: #1976d2;
    }

    .patient-badge.wife {
        background: #fce4ec;
        color: #c2185b;
    }

    .no-data {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }
</style>

<!-- Quick Search -->
<div class="quick-search">
    <div class="row align-items-center">
        <div class="col-md-8">
            <label class="form-label mb-2"><i class="ti ti-search me-2"></i>{{ __('messages.quick_search_medical_file') }}</label>
            <div class="input-group">
                <input type="text" id="quickSearchInput" class="form-control" placeholder="{{ __('messages.enter_file_number') }}">
                <button type="button" id="quickSearchBtn" class="btn btn-primary">
                    <i class="ti ti-search me-1"></i>{{ __('messages.search') }}
                </button>
            </div>
            <div id="searchError" class="text-danger mt-2" style="display: none;"></div>
        </div>
        <div class="col-md-4 quick-actions mt-3 mt-md-0">
            <div class="d-grid gap-2">
                <a href="{{ route('reception.appointments.create') }}" class="btn btn-success">
                    <i class="ti ti-calendar-plus me-2"></i>{{ __('messages.add_appointment') }}
                </a>
                <a href="{{ route('reception.medical-files.create') }}" class="btn btn-primary">
                    <i class="ti ti-file-plus me-2"></i>{{ __('messages.add_medical_file') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary text-white">
                        <i class="ti ti-file-text"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <h6 class="mb-0 text-muted">{{ __('messages.total_medical_files') }}</h6>
                        <h3 class="mb-0 mt-1">{{ $totalFiles }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success text-white">
                        <i class="ti ti-file-plus"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <h6 class="mb-0 text-muted">{{ __('messages.files_this_month') }}</h6>
                        <h3 class="mb-0 mt-1">{{ $filesThisMonth }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning text-white">
                        <i class="ti ti-calendar-event"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <h6 class="mb-0 text-muted">{{ __('messages.total_appointments') }}</h6>
                        <h3 class="mb-0 mt-1">{{ $totalAppointments }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info text-white">
                        <i class="ti ti-calendar-plus"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <h6 class="mb-0 text-muted">{{ __('messages.appointments_this_month') }}</h6>
                        <h3 class="mb-0 mt-1">{{ $appointmentsThisMonth }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <!-- Medical Files by Region -->
    <div class="col-12 mb-4">
        <div class="card chart-card">
            <div class="card-header">
                <h5><i class="ti ti-map-pin me-2"></i>{{ __('messages.files_by_region') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container-large">
                    <canvas id="filesByRegionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Medical Files by Category -->
    <div class="col-md-6 mb-4">
        <div class="card chart-card">
            <div class="card-header">
                <h5><i class="ti ti-folder me-2"></i>{{ __('messages.files_by_category') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container-small">
                    <canvas id="filesByCategoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Medical Files by Month -->
    <div class="col-12 mb-4">
        <div class="card chart-card">
            <div class="card-header">
                <h5><i class="ti ti-chart-line me-2"></i>{{ __('messages.files_by_month') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="filesByMonthChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments by Month -->
    <div class="col-md-6 mb-4">
        <div class="card chart-card">
            <div class="card-header">
                <h5><i class="ti ti-calendar-stats me-2"></i>{{ __('messages.appointments_by_month') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container-small">
                    <canvas id="appointmentsByMonthChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments by Category -->
    <div class="col-md-6 mb-4">
        <div class="card chart-card">
            <div class="card-header">
                <h5><i class="ti ti-chart-pie me-2"></i>{{ __('messages.appointments_by_category') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container-small">
                    <canvas id="appointmentsByCategoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Appointments Table -->
<div class="row">
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-header">
                <h5><i class="ti ti-calendar-time me-2"></i>{{ __('messages.today_appointments') }}</h5>
            </div>
            <div class="card-body">
                @if($todayAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table today-appointments-table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">{{ __('messages.file_number') }}</th>
                                    <th style="width: 35%;">{{ __('messages.patients') }}</th>
                                    <th style="width: 30%;">{{ __('messages.notes') }}</th>
                                    <th style="width: 15%;">{{ __('messages.date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayAppointments as $appointment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $appointment->medicalFile->file_number }}</strong></td>
                                    <td>
                                        @if($appointment->include_husband)
                                            <span class="patient-badge husband">
                                                {{ __('messages.husband') }}: {{ $appointment->medicalFile->husband?->name }}
                                            </span>
                                        @endif
                                        @if($appointment->include_wife)
                                            <span class="patient-badge wife">
                                                {{ __('messages.wife') }}: {{ $appointment->medicalFile->wife?->name }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $appointment->notes ?? '-' }}</td>
                                    <td>{{ $appointment->created_at->format('H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="no-data">
                        <i class="ti ti-calendar-off" style="font-size: 3rem; color: #dee2e6;"></i>
                        <p class="mt-3">{{ __('messages.no_appointments_today') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Quick Search Functionality
document.getElementById('quickSearchBtn').addEventListener('click', performQuickSearch);
document.getElementById('quickSearchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        performQuickSearch();
    }
});

function performQuickSearch() {
    const fileNumber = document.getElementById('quickSearchInput').value.trim();
    const searchError = document.getElementById('searchError');

    if (!fileNumber) {
        searchError.textContent = '{{ __("messages.enter_file_number") }}';
        searchError.style.display = 'block';
        return;
    }

    searchError.style.display = 'none';

    fetch('{{ route("reception.search-file") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ file_number: fileNumber })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            searchError.textContent = data.message;
            searchError.style.display = 'block';
        }
    })
    .catch(error => {
        searchError.textContent = '{{ __("messages.medical_file_not_found") }}';
        searchError.style.display = 'block';
    });
}

// Chart Colors
const colors = {
    primary: '#2c94b8',
    success: '#10b981',
    warning: '#f59e0b',
    info: '#3b82f6',
    danger: '#ef4444',
    purple: '#8b5cf6',
    pink: '#ec4899',
    indigo: '#6366f1',
    teal: '#14b8a6',
    orange: '#f97316'
};

const colorPalette = [
    colors.primary, colors.info, colors.success, colors.warning,
    colors.danger, colors.purple, colors.pink, colors.indigo,
    colors.teal, colors.orange
];

// Files by Region Chart
const filesByRegionData = @json($filesByRegion);
new Chart(document.getElementById('filesByRegionChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(filesByRegionData),
        datasets: [{
            data: Object.values(filesByRegionData),
            backgroundColor: colorPalette,
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 8,
                    font: {
                        size: 11
                    }
                }
            }
        }
    }
});

// Files by Category Chart
const filesByCategoryData = @json($filesByCategory);
new Chart(document.getElementById('filesByCategoryChart'), {
    type: 'pie',
    data: {
        labels: Object.keys(filesByCategoryData),
        datasets: [{
            data: Object.values(filesByCategoryData),
            backgroundColor: colorPalette,
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 8,
                    font: {
                        size: 11
                    }
                }
            }
        }
    }
});

// Files by Month Chart
const filesByMonthData = @json($filesByMonth);
new Chart(document.getElementById('filesByMonthChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(filesByMonthData),
        datasets: [{
            label: '{{ __("messages.medical_files") }}',
            data: Object.values(filesByMonthData),
            backgroundColor: colors.primary,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0,
                    font: {
                        size: 11
                    }
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 11
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Appointments by Month Chart
const appointmentsByMonthData = @json($appointmentsByMonth);
new Chart(document.getElementById('appointmentsByMonthChart'), {
    type: 'line',
    data: {
        labels: Object.keys(appointmentsByMonthData),
        datasets: [{
            label: '{{ __("messages.appointments") }}',
            data: Object.values(appointmentsByMonthData),
            borderColor: colors.info,
            backgroundColor: colors.info + '20',
            fill: true,
            tension: 0.4,
            borderWidth: 2,
            pointRadius: 4,
            pointBackgroundColor: colors.info
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0,
                    font: {
                        size: 11
                    }
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 11
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Appointments by Category Chart
const appointmentsByCategoryData = @json($appointmentsByCategory);
new Chart(document.getElementById('appointmentsByCategoryChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(appointmentsByCategoryData),
        datasets: [{
            label: '{{ __("messages.appointments") }}',
            data: Object.values(appointmentsByCategoryData),
            backgroundColor: colorPalette,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0,
                    font: {
                        size: 11
                    }
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 11
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush
