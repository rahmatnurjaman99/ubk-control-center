<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Transaction Receipt') }} - {{ $transaction->reference }}</title>
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
            <p style="margin:0;font-size:12px;color:#6b7280;">{{ __('Transaction Receipt') }}</p>
        </div>
        <div style="text-align:right;">
            <p style="margin:0;font-size:12px;color:#6b7280;">{{ now()->format('d M Y, H:i') }}</p>
            <p style="margin:0;font-weight:bold;">#{{ $transaction->reference }}</p>
        </div>
    </div>

    <div class="section">
        <h3>{{ __('Details') }}</h3>
        <table>
            <tr>
                <th>{{ __('Title') }}</th>
                <td>{{ $transaction->label }}</td>
            </tr>
            <tr>
                <th>{{ __('Type') }}</th>
                <td>{{ $transaction->type?->getLabel() }}</td>
            </tr>
            <tr>
                <th>{{ __('Amount') }}</th>
                <td>{{ number_format((float) $transaction->amount, 2) }} {{ $transaction->currency }}</td>
            </tr>
            <tr>
                <th>{{ __('Payment Status') }}</th>
                <td><span class="badge">{{ $transaction->payment_status?->getLabel() }}</span></td>
            </tr>
            <tr>
                <th>{{ __('Paid at') }}</th>
                <td>{{ optional($transaction->paid_at)->format('d M Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Payment method') }}</th>
                <td>{{ $transaction->payment_method ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>{{ __('Counterparty') }}</h3>
        <table>
            <tr>
                <th>{{ __('Name') }}</th>
                <td>{{ $transaction->counterparty_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Recorded by') }}</th>
                <td>{{ $transaction->recorder?->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Academic Year') }}</th>
                <td>{{ $transaction->academicYear?->name ?? '-' }}</td>
            </tr>
        </table>
    </div>
@if ($transaction->fees->contains(fn ($fee) => $fee->scholarship))
    <div class="section">
        <h3>{{ __('filament.transactions.sections.scholarships') }}</h3>
        <table>
            <tr>
                <th>{{ __('filament.fees.model.singular') }}</th>
                <th>{{ __('filament.scholarships.model.singular') }}</th>
                <th>{{ __('filament.scholarships.fields.discount') }}</th>
            </tr>
            @foreach ($transaction->fees as $fee)
                @continue(! $fee->scholarship)
                <tr>
                    <td>{{ $fee->title }}</td>
                    <td>{{ $fee->scholarship?->name ?? '-' }}</td>
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
            @endforeach
        </table>
    </div>
@endif
</body>
</html>
