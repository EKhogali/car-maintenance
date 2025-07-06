<div style="font-family: Arial, sans-serif; direction: rtl; color: #000; font-size: 14px; padding: 20px;">

<!-- Header -->
<div style="display:flex; align-items:center; border-bottom:2px solid #3e2f92; padding-bottom:10px; margin-bottom:20px;">
    <img src="{{ asset('storage/logo.jpg') }}" alt="logo" style="width:90px; height:auto; margin-left:25px;">
    <div>
        <h2 style="margin:0; color:#3e2f92;">شركة أقساط لبيع السيارات</h2>
        <p style="margin:0;">مركز الصيانة فرع جنزور</p>
    </div>
</div>

@php
    $serviceTotal  = $record->services->sum('price');
    $partsTotal    = $record->partUsages->sum(fn($u) => $u->unit_price * $u->quantity);
    $cost          = $serviceTotal + $partsTotal;
    $discount      = $record->discount ?? 0;
    $due           = max(0, $cost - $discount);
    $advance       = $record->advance_payment ?? 0;
    $remained      = max(0, $due - $advance);

    $mechanicPct   = $record->mechanic_pct ?? 0;
    $mechanicAmount = round(($serviceTotal - $discount) * $mechanicPct / 100, 2);

    $supervisorPct = 10;
    $paidPartsOnly = $record->partUsages->filter(fn($u) => $u->unit_price > 0)
                        ->sum(fn($u) => $u->unit_price * $u->quantity);
    $supervisorAmount = round(($serviceTotal + $paidPartsOnly) * $supervisorPct / 100, 2);

    $companyAmount = max(0, ($advance + $remained) - $mechanicAmount - $supervisorAmount);
@endphp

<!-- Breakdown -->
<div style="background: #f0f0f0; padding: 8px; font-weight: bold;">تقرير مالي</div>

<!-- Maintenance Record Info -->
<table style="width:100%; border-collapse:collapse; margin-bottom: 20px;">
    <tr>
        <td style="padding:6px;"><strong>رقم الصيانة:</strong></td>
        <td style="padding:6px;">{{ $record->id }}</td>
        <td style="padding:6px;"><strong>تاريخ الخدمة:</strong></td>
        <td style="padding:6px;">{{ $record->service_date }}</td>
    </tr>
    <tr>
        <td style="padding:6px;"><strong>الفني:</strong></td>
        <td style="padding:6px;">{{ $record->mechanic->name ?? '-' }}</td>
        <td style="padding:6px;"><strong>نسبة الفني:</strong></td>
        <td style="padding:6px;">{{ $record->mechanic_pct }}%</td>
    </tr>
    <tr>
        <td style="padding:6px;"><strong>السيارة:</strong></td>
        <td style="padding:6px;" colspan="3">
            {{ $record->car->make }} {{ $record->car->model }} -
            {{ $record->car->license_plate }}<br>
            VIN: {{ $record->car->vin }}
        </td>
    </tr>
    <tr>
        <td style="padding:6px;"><strong>العميل:</strong></td>
        <td style="padding:6px;" colspan="3">{{ $record->car->customer->name ?? '-' }}</td>
    </tr>
</table>




<!-- Totals Summary -->
<div style="background: #c9a15d; color: white; padding: 8px; margin-top: 20px; font-weight: bold;">الإجماليات</div>
<table style="width: 100%; border-collapse: collapse;" border="1">
    <tr>
        <td style="padding: 6px;"><strong>إجمالي الخدمات:</strong></td>
        <td style="padding: 6px;">{{ number_format($serviceTotal, 2) }} د.ل</td>
    </tr>
    <tr>
        <td style="padding: 6px;"><strong>إجمالي قطع الغيار:</strong></td>
        <td style="padding: 6px;">{{ number_format($partsTotal, 2) }} د.ل</td>
    </tr>
    <tr>
        <td style="padding: 6px;"><strong>الخصم:</strong></td>
        <td style="padding: 6px;">{{ number_format($discount, 2) }} د.ل</td>
    </tr>
    <tr>
        <td style="padding: 6px;"><strong>الصافي بعد الخصم:</strong></td>
        <td style="padding: 6px;">{{ number_format($due, 2) }} د.ل</td>
    </tr>

</table>

<table style="width: 100%; border-collapse: collapse; margin-top: 10px;" border="1">
    <thead style="background: #f8e4b8;">
        <tr>
            <th style="padding: 6px;">الجهة</th>
            <th style="padding: 6px;">النسبة</th>
            <th style="padding: 6px;">المبلغ (د.ل)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding: 6px;">الفني ({{ $record->mechanic->name ?? '-' }})</td>
            <td style="padding: 6px;">{{ number_format($mechanicPct, 2) }}%</td>
            <td style="padding: 6px;">{{ number_format($mechanicAmount, 2) }}</td>
        </tr>
        <tr>
            <td style="padding: 6px;">المشرف</td>
            <td style="padding: 6px;">{{ $supervisorPct }}%</td>
            <td style="padding: 6px;">{{ number_format($supervisorAmount, 2) }}</td>
        </tr>
        <tr style="font-weight: bold; background: #f5f5f5;">
            <td style="padding: 6px;">حصة الشركة</td>
            <td style="padding: 6px;">باقي المبلغ</td>
            <td style="padding: 6px;">{{ number_format($companyAmount, 2) }}</td>
        </tr>
    </tbody>
</table>

<!-- Footer -->
<div style="border-top: 1px solid #ccc; padding-top: 12px; font-size: 13px; text-align: center; margin-top: 40px; color: #3e2f92; line-height: 1.8;">
    <div>الموقع الإلكتروني: www.aqssat.ly</div>
    <div>البريد الإلكتروني: aqssatcar@gmail.com</div>
    <div>الهـواتف: 0918043777 / 0928043777</div>
    <div style="color: #888;">{{ now()->format('Y-m-d H:i:s') }}</div>
</div>

</div>
