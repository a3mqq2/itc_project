<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.appointments_list') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
            margin: 0;
            padding: 20px;
            font-size: 14px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2d3748;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
            color: #1a1a1a;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .info-section {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 4px;
        }

        .info-section p {
            margin: 5px 0;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        th {
            background: #2d3748;
            color: white;
            font-weight: 600;
            font-size: 13px;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        .patient-badge {
            display: inline-block;
            padding: 3px 8px;
            margin: 2px;
            border-radius: 3px;
            font-size: 11px;
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

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        @media print {
            body {
                padding: 10px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ __('messages.app_name') }}</h1>
        <p>{{ __('messages.appointments_list') }}</p>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <p><strong>{{ __('messages.date') }}:</strong> {{ now()->format('Y-m-d H:i') }}</p>
        <p><strong>{{ __('messages.total') }}:</strong> {{ $appointments->count() }} {{ __('messages.appointments') }}</p>
    </div>

    <!-- Appointments Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 12%;">{{ __('messages.file_number') }}</th>
                <th style="width: 25%;">{{ __('messages.patients') }}</th>
                <th style="width: 38%;">{{ __('messages.notes') }}</th>
                <th style="width: 20%;">{{ __('messages.date') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
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
                <td>{{ $appointment->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 30px;">
                    {{ __('messages.no_appointments_found') }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>{{ __('messages.app_name') }}</p>
        <p>{{ __('messages.all_rights_reserved') }} &copy; {{ date('Y') }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
