<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Fee Receipt') }} - {{ $fee->reference }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; color: #1f2937; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .section { margin-bottom: 16px; }
        .section h3 { margin-bottom: 6px; text-transform: uppercase; font-size: 12px; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 9999px; font-size: 12px; background: #e5e7eb; color: #111827; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1 style="margin:0;font-size:24px;">{{ config('app.name') }}</h1>
            <p style="margin:0;font-size:12px;color:#6b7280;">{{ __('Fee Receipt') }}</p>
        </div>
        <div style="text-align:right;">
            <p style="margin:0;font-size:12px;color:#6b7280;">{{ now()->format('d M Y, H:i') }}</p>
            <p style="margin:0;font-weight:bold;">#{{ $fee->reference }}</p>
        </div>
    </div>

    <div class="section">
        <h3>{{ __('Fee Details') }}</h3>
        <table>
            <tr>
                <th>{{ __('Title') }}</th>
                <td>{{ $fee->title }}</td>
            </tr>
            <tr>
                <th>{{ __('Type') }}</th>
                <td>{{ $fee->type?->getLabel() }}</td>
            </tr>
            <tr>
                <th>{{ __('Amount') }}</th>
                <td>{{ number_format((float) $fee->amount, 2) }} {{ $fee->currency }}</td>
            </tr>
            <tr>
                <th>{{ __('Status') }}</th>
                <td><span class="badge">{{ $fee->status?->getLabel() }}</span></td>
            </tr>
            <tr>
                <th>{{ __('Due date') }}</th>
                <td>{{ optional($fee->due_date)->format('d M Y') ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Paid at') }}</th>
                <td>{{ optional($fee->paid_at)->format('d M Y H:i') ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>{{ __('Student') }}</h3>
        <table>
            <tr>
                <th>{{ __('Student') }}</th>
                <td>{{ $fee->student?->full_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Guardian') }}</th>
                <td>{{ $fee->student?->guardian?->full_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Academic Year') }}</th>
                <td>{{ $fee->academicYear?->name ?? '-' }}</td>
            </tr>
        </table>
    </div>
@if ($fee->scholarship)
    <div class="section">
        <h3>{{ __('filament.scholarships.model.singular') }}</h3>
        <table>
            <tr>
                <th>{{ __('filament.scholarships.fields.name') }}</th>
                <td>{{ $fee->scholarship?->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('filament.scholarships.fields.discount') }}</th>
                <td>
                    @php
                        $lines = [];
                        if ($fee->scholarship_discount_percent !== null) {
                            $lines[] = $fee->scholarship_discount_percent . '%';
                        }
                        if ((float) ($fee->scholarship_discount_amount ?? 0) > 0) {
                            $lines[] = number_format((float) $fee->scholarship_discount_amount, 2) . ' ' . ($fee->currency ?? 'IDR');
                        }
                    @endphp
                    {{ implode(' • ', $lines) }}
                </td>
            </tr>
        </table>
    </div>
@endif
</body>
</html>
