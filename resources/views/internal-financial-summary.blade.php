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
    // Dynamically calculate totals
    $servicesTotal = $record->services->sum('price');  // using related service records
    $partsTotal = $record->partUsages->sum(fn ($u) => $u->quantity * $u->unit_price); // from related part usages

    // Read stored values from DB if available
    $discount = $record->discount ?? 0;
    $mechanicPct = $record->mechanic_pct ?? 0;
    $mechanicAmount = $record->mechanic_amount ?? 0;
    $supervisorPct = $record->supervisor_pct ?? 0;
    $supervisorAmount = $record->supervisor_amount ?? 0;
    $companyAmount = $record->company_amount ?? 0;
    $companyShare = ($servicesTotal - $discount) - $mechanicAmount - $supervisorAmount;

    $totalAfterDiscount = max(0, $servicesTotal - $discount);

    $mechanicName = $record->mechanic->name ?? \App\Models\Mechanic::find($record->mechanic_id)?->name ?? '-';
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
        <td style="padding:6px;"><strong>الزبون:</strong></td>
        <td style="padding:6px;" colspan="3">{{ $record->car->customer->name ?? '-' }}</td>
    </tr>
</table>




<!-- Totals Summary -->
<div style="background: #c9a15d; color: white; padding: 8px; margin-top: 20px; font-weight: bold;">الإجماليات</div>


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
            <td style="padding: 6px;">إجمالي الخدمات:</td>
            <td style="padding: 6px;">/</td>
            <td style="padding: 6px;">{{ number_format($servicesTotal, 2) }}</td>
        </tr>
        <tr>
            <td style="padding: 6px;">التخفيض:</td>
            <td style="padding: 6px;">/</td>
            <td style="padding: 6px;">{{ number_format($discount, 2) }}</td>
        </tr>
        <tr>
            <td style="padding: 6px;">الاجمالي بعد الخصم:</td>
            <td style="padding: 6px;">/</td>
            <td style="padding: 6px;">{{ number_format($totalAfterDiscount, 2) }}</td>
        </tr>
        <tr>
            <td style="padding: 6px;">الفني ({{ $mechanicName ?? '-' }}) من اجمالي الخدمات </td>
            <td style="padding: 6px;">{{ number_format($mechanicPct, 2) }}%</td>
            <td style="padding: 6px;">{{ number_format($mechanicAmount, 2) }}</td>
        </tr>
        <tr>
            <td style="padding: 6px;">اجمالي قطع الغيار: </td>
            <td style="padding: 6px;">/</td>
            <td style="padding: 6px;">{{ number_format($partsTotal, 2) }}</td>
        </tr>
        <tr>
            <td style="padding: 6px;">حصة المدير التنفيذي: </td>
            <td style="padding: 6px;">{{ $supervisorPct }}%</td>
            <td style="padding: 6px;">{{ number_format($supervisorAmount, 2) }}</td>
        </tr>
        <tr style="font-weight: bold; background: #f5f5f5;">
            <td style="padding: 6px;">حصة الشركة</td>
            <td style="padding: 6px;">باقي المبلغ</td>
            <td style="padding: 6px;">{{ number_format($companyShare, 2) }}</td>
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
