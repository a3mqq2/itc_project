<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.medical_file') }} - {{ $medicalFile->file_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            line-height: 1.6;
            padding: 20px;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2c94b8;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #2c94b8;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            background: linear-gradient(135deg, #2c94b8 0%, #39b8d7 100%);
            color: white;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-size: 18px;
            border-radius: 5px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .info-item label {
            font-weight: bold;
            color: #2c94b8;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .info-item p {
            color: #333;
            font-size: 14px;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="background: #2c94b8; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            {{ __('messages.print') }}
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>{{ __('messages.app_name') }}</h1>
        <p>{{ __('messages.medical_file') }}</p>
    </div>

    <!-- File Information -->
    <div class="section">
        <div class="section-title">{{ __('messages.file_info') }}</div>
        <div class="info-grid">
            <div class="info-item">
                <label>{{ __('messages.file_number') }}</label>
                <p>{{ $medicalFile->file_number }}</p>
            </div>
            <div class="info-item">
                <label>{{ __('messages.registration_date') }}</label>
                <p>{{ $medicalFile->registration_date->format('Y-m-d') }}</p>
            </div>
            <div class="info-item">
                <label>{{ __('messages.category') }}</label>
                <p>{{ $medicalFile->category?->name ?? '-' }}</p>
            </div>
            <div class="info-item">
                <label>{{ __('messages.region') }}</label>
                <p>{{ $medicalFile->region ?? '-' }}</p>
            </div>
            <div class="info-item full-width">
                <label>{{ __('messages.diagnosis') }}</label>
                <p>{{ $medicalFile->diagnosis ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Husband Information -->
    <div class="section">
        <div class="section-title">{{ __('messages.husband_information') }}</div>
        <div class="info-grid">
            <div class="info-item">
                <label>{{ __('messages.name') }}</label>
                <p>{{ $medicalFile->husband?->name ?? '-' }}</p>
            </div>
            <div class="info-item">
                <label>{{ __('messages.national_id') }}</label>
                <p>{{ $medicalFile->husband?->national_id ?? '-' }}</p>
            </div>
            <div class="info-item">
                <label>{{ __('messages.registry_number') }}</label>
                <p>{{ $medicalFile->husband?->registry_number ?? '-' }}</p>
            </div>
            <div class="info-item">
                <label>{{ __('messages.date_of_birth') }}</label>
                <p>{{ $medicalFile->husband?->dob?->format('Y-m-d') ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Wife Information -->
    <div class="section">
        <div class="section-title">{{ __('messages.wife_information') }}</div>
        <div class="info-grid">
            <div class="info-item">
                <label>{{ __('messages.name') }}</label>
                <p>{{ $medicalFile->wife?->name ?? '-' }}</p>
            </div>
            <div class="info-item">
                <label>{{ __('messages.national_id') }}</label>
                <p>{{ $medicalFile->wife?->national_id ?? '-' }}</p>
            </div>
            <div class="info-item">
                <label>{{ __('messages.registry_number') }}</label>
                <p>{{ $medicalFile->wife?->registry_number ?? '-' }}</p>
            </div>
            <div class="info-item">
                <label>{{ __('messages.date_of_birth') }}</label>
                <p>{{ $medicalFile->wife?->dob?->format('Y-m-d') ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>{{ __('messages.app_name') }}</p>
        <p>{{ __('messages.all_rights_reserved') }} &copy; {{ date('Y') }}</p>
        <p style="margin-top: 10px;">{{ __('messages.print') }}: {{ date('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
