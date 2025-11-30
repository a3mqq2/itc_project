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
    .detail-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    .detail-card .card-header {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    .detail-card .card-header h5 {
        color: #212529;
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .detail-card .card-header .btn {
        border-radius: 4px;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border: none;
        border-bottom: 3px solid transparent;
        background: transparent;
    }

    .nav-tabs .nav-link:hover {
        color: #2c94b8;
        border-color: transparent;
    }

    .nav-tabs .nav-link.active {
        color: #2c94b8;
        border-bottom-color: #2c94b8;
        background: transparent;
    }

    .nav-tabs .nav-link i {
        margin-left: 0.35rem;
    }

    .section-header {
        background: #f8f9fa;
        padding: 0.75rem 1rem;
        border-radius: 4px;
        margin-bottom: 1rem;
        border-left: 3px solid #2c94b8;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-header h5 {
        color: #212529;
        font-weight: 600;
        margin: 0;
        font-size: 1rem;
    }

    .section-header i {
        color: #6c757d;
        margin-right: 0.5rem;
    }

    .info-row {
        background: #ffffff;
        padding: 0.75rem;
        border-radius: 4px;
        margin-bottom: 0.75rem;
        border: 1px solid #dee2e6;
    }

    .info-row:hover {
        background-color: #f8f9fa;
    }

    .info-label {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.25rem;
        font-size: 0.85rem;
    }

    .info-label i {
        margin-right: 0.35rem;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .info-value {
        color: #495057;
        font-size: 0.95rem;
        margin: 0;
        padding-left: 1.25rem;
    }

    .badge-number {
        background: #2c94b8;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 3px;
        font-weight: 500;
        font-size: 0.95rem;
    }

    /* Attachments Table */
    .attachments-table {
        width: 100%;
        border-collapse: collapse;
    }

    .attachments-table th {
        background: #f8f9fa;
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        text-align: right;
        font-size: 0.9rem;
    }

    .attachments-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .attachments-table tr:hover {
        background: #f8f9fa;
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .file-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .file-icon.pdf {
        background: #fff0f0;
        color: #dc3545;
    }

    .file-icon.doc {
        background: #f0f0ff;
        color: #0d6efd;
    }

    .file-icon.image {
        background: #f0fff0;
        color: #198754;
    }

    .file-icon.default {
        background: #f0f7ff;
        color: #2c94b8;
    }

    .file-name {
        font-weight: 500;
        color: #212529;
        font-size: 0.9rem;
    }

    .file-desc {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 2px;
    }

    .no-attachments {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .no-attachments i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    .action-btns .btn {
        padding: 0.35rem 0.65rem;
        font-size: 0.85rem;
    }

    /* Modal styles */
    .modal-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-footer {
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    .upload-form .form-label {
        font-weight: 500;
        color: #495057;
    }

    .delete-modal-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #fff0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .delete-modal-icon i {
        font-size: 2rem;
        color: #dc3545;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card detail-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">
                        {{ __('messages.medical_file') }} -
                        <span class="badge-number">{{ $medicalFile->file_number }}</span>
                    </h5>
                    <div>
                        <a href="{{ route('reception.medical-files.edit', $medicalFile) }}" class="btn btn-sm btn-warning">
                            <i class="ti ti-edit me-1"></i>{{ __('messages.update') }}
                        </a>
                        <a href="{{ route('reception.medical-files.print', $medicalFile) }}" class="btn btn-sm btn-secondary" target="_blank">
                            <i class="ti ti-printer me-1"></i>{{ __('messages.print') }}
                        </a>
                        <a href="{{ route('reception.medical-files.pdf', $medicalFile) }}" class="btn btn-sm btn-primary">
                            <i class="ti ti-file-download me-1"></i>{{ __('messages.export_pdf') }}
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="medicalFileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                            <i class="ti ti-info-circle"></i> {{ __('messages.information') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="attachments-tab" data-bs-toggle="tab" data-bs-target="#attachments" type="button" role="tab">
                            <i class="ti ti-paperclip"></i> {{ __('messages.attachments') }}
                            @if($medicalFile->attachments->count() > 0)
                                <span class="badge bg-primary ms-1">{{ $medicalFile->attachments->count() }}</span>
                            @endif
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="medicalFileTabsContent">
                    <!-- Information Tab -->
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <!-- File Information -->
                        <div class="mb-4">
                            <div class="section-header">
                                <h5><i class="ti ti-file"></i>{{ __('messages.file_info') }}</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-hash"></i>{{ __('messages.file_number') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->file_number }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-calendar"></i>{{ __('messages.registration_date') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->registration_date->format('Y-m-d') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-folder"></i>{{ __('messages.category') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->category?->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-map-pin"></i>{{ __('messages.region') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->region ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-stethoscope"></i>{{ __('messages.diagnosis') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->diagnosis ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Husband Information -->
                        <div class="mb-4">
                            <div class="section-header">
                                <h5><i class="ti ti-user"></i>{{ __('messages.husband_information') }}</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-user"></i>{{ __('messages.name') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->husband?->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-id"></i>{{ __('messages.national_id') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->husband?->national_id ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-file-text"></i>{{ __('messages.registry_number') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->husband?->registry_number ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-calendar-event"></i>{{ __('messages.date_of_birth') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->husband?->dob?->format('Y-m-d') ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Wife Information -->
                        <div class="mb-4">
                            <div class="section-header">
                                <h5><i class="ti ti-user"></i>{{ __('messages.wife_information') }}</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-user"></i>{{ __('messages.name') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->wife?->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-id"></i>{{ __('messages.national_id') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->wife?->national_id ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-file-text"></i>{{ __('messages.registry_number') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->wife?->registry_number ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="ti ti-calendar-event"></i>{{ __('messages.date_of_birth') }}
                                        </div>
                                        <p class="info-value">{{ $medicalFile->wife?->dob?->format('Y-m-d') ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Tab -->
                    <div class="tab-pane fade" id="attachments" role="tabpanel">
                        <!-- Section Header with Add Button -->
                        <div class="section-header mb-3">
                            <h5><i class="ti ti-files"></i>{{ __('messages.attachments') }} ({{ $medicalFile->attachments->count() }})</h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <i class="ti ti-plus me-1"></i>{{ __('messages.add_attachment') }}
                            </button>
                        </div>

                        @if($medicalFile->attachments->count() > 0)
                            <div class="table-responsive">
                                <table class="attachments-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%;">{{ __('messages.file_name') }}</th>
                                            <th style="width: 15%;">{{ __('messages.file_size') }}</th>
                                            <th style="width: 15%;">{{ __('messages.date') }}</th>
                                            <th style="width: 30%; text-align: center;">{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($medicalFile->attachments as $attachment)
                                            <tr>
                                                <td>
                                                    <div class="file-info">
                                                        <div class="file-icon {{ Str::contains($attachment->file_type, 'pdf') ? 'pdf' : (Str::contains($attachment->file_type, 'image') ? 'image' : 'default') }}">
                                                            <i class="{{ $attachment->file_icon }}"></i>
                                                        </div>
                                                        <div>
                                                            <div class="file-name">{{ Str::limit($attachment->original_name, 35) }}</div>
                                                            @if($attachment->description)
                                                                <div class="file-desc">{{ Str::limit($attachment->description, 40) }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $attachment->file_size_formatted }}</td>
                                                <td>{{ $attachment->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <div class="action-btns d-flex gap-1 justify-content-center">
                                                        <a href="{{ route('reception.medical-files.attachments.download', [$medicalFile, $attachment]) }}" class="btn btn-outline-primary btn-sm" title="{{ __('messages.download') }}">
                                                            <i class="ti ti-download"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $attachment->id }}" title="{{ __('messages.edit') }}">
                                                            <i class="ti ti-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $attachment->id }}" title="{{ __('messages.delete') }}">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="no-attachments">
                                <i class="ti ti-files-off"></i>
                                <p class="mb-3">{{ __('messages.no_attachments') }}</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="ti ti-plus me-1"></i>{{ __('messages.add_attachment') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('reception.medical-files.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>{{ __('messages.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-upload me-2"></i>{{ __('messages.upload_attachment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('reception.medical-files.attachments.store', $medicalFile) }}" method="POST" enctype="multipart/form-data" class="upload-form">
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

<!-- Edit & Delete Modals -->
@foreach($medicalFile->attachments as $attachment)
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal{{ $attachment->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-edit me-2"></i>{{ __('messages.edit_attachment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('reception.medical-files.attachments.update', [$medicalFile, $attachment]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.file_name') }}</label>
                            <input type="text" class="form-control" value="{{ $attachment->original_name }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.description') }}</label>
                            <textarea name="description" class="form-control" rows="3">{{ $attachment->description }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
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
