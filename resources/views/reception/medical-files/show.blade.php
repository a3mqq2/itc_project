@extends('layouts.app')

@section('title', __('messages.medical_file') . ' - ' . $medicalFile->file_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sections') }}">{{ __('messages.sections') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reception.home') }}">{{ __('messages.reception') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reception.medical-files.index') }}">{{ __('messages.medical_files') }}</a></li>
    <li class="breadcrumb-item active">{{ $medicalFile->file_number }}</li>
@endsection

@section('skip-dashboard-analytics')
@endsection

@section('content')
<style>
    .file-header {
        background: linear-gradient(135deg, #2c94b8 0%, #1a5f7a 100%);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .file-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    }

    .file-header .file-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .file-header .file-meta {
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .file-header .header-actions .btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .file-header .header-actions .btn:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
    }

    .info-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .info-card .card-header {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #e0e0e0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-card .card-header .icon-wrapper {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .info-card .card-header .icon-wrapper.primary {
        background: #e3f2fd;
        color: #1976d2;
    }

    .info-card .card-header .icon-wrapper.success {
        background: #e8f5e9;
        color: #388e3c;
    }

    .info-card .card-header .icon-wrapper.info {
        background: #e0f7fa;
        color: #0097a7;
    }

    .info-card .card-header .icon-wrapper.warning {
        background: #fff3e0;
        color: #f57c00;
    }

    .info-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        color: #333;
        font-size: 1.1rem;
    }

    .info-card .card-body {
        padding: 1.5rem;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1rem;
        border-right: 3px solid #2c94b8;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-item .icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
        color: #2c94b8;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .info-item .content {
        flex: 1;
    }

    .info-item .label {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .info-item .value {
        font-size: 1rem;
        color: #212529;
        font-weight: 600;
        margin: 0;
    }

    .patient-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        overflow: hidden;
        height: 100%;
    }

    .patient-card .patient-header {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .patient-card .patient-header.husband {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-bottom: 2px solid #1976d2;
    }

    .patient-card .patient-header.wife {
        background: linear-gradient(135deg, #fce4ec 0%, #f8bbd9 100%);
        border-bottom: 2px solid #c2185b;
    }

    .patient-card .avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .patient-card .patient-header.husband .avatar {
        background: #1976d2;
        color: white;
    }

    .patient-card .patient-header.wife .avatar {
        background: #c2185b;
        color: white;
    }

    .patient-card .patient-info h5 {
        margin: 0 0 0.25rem 0;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .patient-card .patient-header.husband .patient-info h5 {
        color: #1565c0;
    }

    .patient-card .patient-header.wife .patient-info h5 {
        color: #ad1457;
    }

    .patient-card .patient-info span {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    .patient-card .patient-body {
        padding: 1.5rem;
    }

    .patient-card .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .patient-card .detail-row:last-child {
        border-bottom: none;
    }

    .patient-card .detail-row .label {
        color: #6c757d;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .patient-card .detail-row .value {
        font-weight: 600;
        color: #333;
    }

    /* Quick Stats */
    .quick-stats {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-item {
        flex: 1;
        background: white;
        border-radius: 10px;
        padding: 1.25rem;
        border: 1px solid #e0e0e0;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .stat-item .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        margin: 0 auto 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-item.appointments .stat-icon {
        background: #e8f5e9;
        color: #388e3c;
    }

    .stat-item.attachments .stat-icon {
        background: #fff3e0;
        color: #f57c00;
    }

    .stat-item.days .stat-icon {
        background: #e3f2fd;
        color: #1976d2;
    }

    .stat-item .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.25rem;
    }

    .stat-item .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
    }

    /* Tabs */
    .nav-pills-custom {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .nav-pills-custom .nav-link {
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .nav-pills-custom .nav-link:hover {
        color: #2c94b8;
        background: rgba(44, 148, 184, 0.1);
    }

    .nav-pills-custom .nav-link.active {
        background: #2c94b8;
        color: white;
        box-shadow: 0 4px 12px rgba(44, 148, 184, 0.3);
    }

    .nav-pills-custom .nav-link i {
        margin-left: 0.5rem;
    }

    /* Attachments */
    .attachment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }

    .attachment-item {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .attachment-item:hover {
        border-color: #2c94b8;
        box-shadow: 0 4px 15px rgba(44, 148, 184, 0.15);
    }

    .attachment-item .file-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .attachment-item .file-icon.pdf {
        background: #ffebee;
        color: #c62828;
    }

    .attachment-item .file-icon.image {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .attachment-item .file-icon.doc {
        background: #e3f2fd;
        color: #1565c0;
    }

    .attachment-item .file-icon.default {
        background: #f5f5f5;
        color: #616161;
    }

    .attachment-item .file-info {
        flex: 1;
        min-width: 0;
    }

    .attachment-item .file-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .attachment-item .file-meta {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .attachment-item .file-actions {
        display: flex;
        gap: 0.5rem;
    }

    .attachment-item .file-actions .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .no-attachments {
        text-align: center;
        padding: 4rem 2rem;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .no-attachments i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    .no-attachments h5 {
        color: #6c757d;
        margin-bottom: 1rem;
    }

    /* Modal */
    .modal-content {
        border-radius: 12px;
        border: none;
    }

    .modal-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        border-radius: 12px 12px 0 0;
    }

    .modal-footer {
        background: #f8f9fa;
        border-top: 1px solid #e0e0e0;
        border-radius: 0 0 12px 12px;
    }

    .delete-modal-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #ffebee;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .delete-modal-icon i {
        font-size: 2.5rem;
        color: #c62828;
    }
</style>

<!-- File Header -->
<div class="file-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="file-number">
                <i class="ti ti-file-text me-2"></i>{{ $medicalFile->file_number }}
            </div>
            <div class="file-meta">
                <span><i class="ti ti-calendar me-1"></i>{{ __('messages.registration_date') }}: {{ $medicalFile->registration_date->format('Y-m-d') }}</span>
                @if($medicalFile->category)
                    <span class="mx-3">|</span>
                    <span><i class="ti ti-folder me-1"></i>{{ $medicalFile->category->name }}</span>
                @endif
                @if($medicalFile->region)
                    <span class="mx-3">|</span>
                    <span><i class="ti ti-map-pin me-1"></i>{{ $medicalFile->region }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="header-actions d-flex gap-2 justify-content-md-end mt-3 mt-md-0">
                <a href="{{ route('reception.medical-files.edit', $medicalFile) }}" class="btn btn-sm">
                    <i class="ti ti-edit me-1"></i>{{ __('messages.update') }}
                </a>
                <a href="{{ route('reception.medical-files.print', $medicalFile) }}" class="btn btn-sm" target="_blank">
                    <i class="ti ti-printer me-1"></i>{{ __('messages.print') }}
                </a>
                <a href="{{ route('reception.medical-files.pdf', $medicalFile) }}" class="btn btn-sm">
                    <i class="ti ti-file-download me-1"></i>PDF
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Tabs Navigation -->
<ul class="nav nav-pills nav-pills-custom" id="fileTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="info-tab" data-bs-toggle="pill" data-bs-target="#info" type="button">
            <i class="ti ti-info-circle"></i> {{ __('messages.information') }}
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="attachments-tab" data-bs-toggle="pill" data-bs-target="#attachments" type="button">
            <i class="ti ti-paperclip"></i> {{ __('messages.attachments') }}
            @if($medicalFile->attachments->count() > 0)
                <span class="badge bg-white text-primary ms-1">{{ $medicalFile->attachments->count() }}</span>
            @endif
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="fileTabsContent">
    <!-- Information Tab -->
    <div class="tab-pane fade show active" id="info" role="tabpanel">
        <!-- Patients Row -->
        <div class="row mb-4">
            <!-- Husband Card -->
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="patient-card">
                    <div class="patient-header husband">
                        <div class="avatar">
                            <i class="ti ti-user"></i>
                        </div>
                        <div class="patient-info">
                            <h5>{{ $medicalFile->husband?->name ?? '-' }}</h5>
                            <span>{{ __('messages.husband') }}</span>
                        </div>
                    </div>
                    <div class="patient-body">
                        <div class="detail-row">
                            <span class="label"><i class="ti ti-id"></i>{{ __('messages.national_id') }}</span>
                            <span class="value">{{ $medicalFile->husband?->national_id ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label"><i class="ti ti-file-text"></i>{{ __('messages.registry_number') }}</span>
                            <span class="value">{{ $medicalFile->husband?->registry_number ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label"><i class="ti ti-calendar"></i>{{ __('messages.date_of_birth') }}</span>
                            <span class="value">{{ $medicalFile->husband?->dob?->format('Y-m-d') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wife Card -->
            <div class="col-md-6">
                <div class="patient-card">
                    <div class="patient-header wife">
                        <div class="avatar">
                            <i class="ti ti-user"></i>
                        </div>
                        <div class="patient-info">
                            <h5>{{ $medicalFile->wife?->name ?? '-' }}</h5>
                            <span>{{ __('messages.wife') }}</span>
                        </div>
                    </div>
                    <div class="patient-body">
                        <div class="detail-row">
                            <span class="label"><i class="ti ti-id"></i>{{ __('messages.national_id') }}</span>
                            <span class="value">{{ $medicalFile->wife?->national_id ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label"><i class="ti ti-file-text"></i>{{ __('messages.registry_number') }}</span>
                            <span class="value">{{ $medicalFile->wife?->registry_number ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label"><i class="ti ti-calendar"></i>{{ __('messages.date_of_birth') }}</span>
                            <span class="value">{{ $medicalFile->wife?->dob?->format('Y-m-d') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Diagnosis Card -->
        @if($medicalFile->diagnosis)
        <div class="info-card">
            <div class="card-header">
                <div class="icon-wrapper warning">
                    <i class="ti ti-stethoscope"></i>
                </div>
                <h5>{{ __('messages.diagnosis') }}</h5>
            </div>
            <div class="card-body">
                <p class="mb-0" style="line-height: 1.8;">{{ $medicalFile->diagnosis }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Attachments Tab -->
    <div class="tab-pane fade" id="attachments" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">{{ __('messages.attachments') }} ({{ $medicalFile->attachments->count() }})</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="ti ti-plus me-1"></i>{{ __('messages.add_attachment') }}
            </button>
        </div>

        @if($medicalFile->attachments->count() > 0)
            <div class="attachment-grid">
                @foreach($medicalFile->attachments as $attachment)
                    <div class="attachment-item">
                        <div class="file-icon {{ Str::contains($attachment->file_type, 'pdf') ? 'pdf' : (Str::contains($attachment->file_type, 'image') ? 'image' : 'default') }}">
                            <i class="{{ $attachment->file_icon }}"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name" title="{{ $attachment->original_name }}">{{ $attachment->original_name }}</div>
                            <div class="file-meta">
                                {{ $attachment->file_size_formatted }} - {{ $attachment->created_at->format('Y-m-d') }}
                            </div>
                        </div>
                        <div class="file-actions">
                            <a href="{{ route('reception.medical-files.attachments.download', [$medicalFile, $attachment]) }}" class="btn btn-outline-primary btn-sm" title="{{ __('messages.download') }}">
                                <i class="ti ti-download"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $attachment->id }}" title="{{ __('messages.delete') }}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-attachments">
                <i class="ti ti-files-off d-block"></i>
                <h5>{{ __('messages.no_attachments') }}</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="ti ti-plus me-1"></i>{{ __('messages.add_attachment') }}
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Back Button -->
<div class="mt-4">
    <a href="{{ route('reception.medical-files.index') }}" class="btn btn-secondary">
        <i class="ti ti-arrow-left me-1"></i>{{ __('messages.back') }}
    </a>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-upload me-2"></i>{{ __('messages.upload_attachment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('reception.medical-files.attachments.store', $medicalFile) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.choose_file') }} <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required>
                        <div class="form-text">{{ __('messages.max_file_size') }}: 10MB</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.description') }} <span class="text-muted">({{ __('messages.optional') }})</span></label>
                        <textarea name="description" class="form-control" rows="3" placeholder="{{ __('messages.description') }}..."></textarea>
                    </div>
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

<!-- Delete Modals -->
@foreach($medicalFile->attachments as $attachment)
    <div class="modal fade" id="deleteModal{{ $attachment->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="delete-modal-icon">
                        <i class="ti ti-trash"></i>
                    </div>
                    <h5 class="mb-2">{{ __('messages.confirm_delete') }}</h5>
                    <p class="text-muted mb-0">{{ __('messages.delete_attachment_confirm') }}</p>
                    <p class="fw-bold">{{ $attachment->original_name }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <form action="{{ route('reception.medical-files.attachments.destroy', [$medicalFile, $attachment]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-trash me-1"></i>{{ __('messages.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
