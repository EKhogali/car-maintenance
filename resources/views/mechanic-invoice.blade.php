@php
    $servicesTotal = $record->services->sum('price');
    $partsTotal = $record->partUsages->sum(fn ($u) => $u->quantity * $u->unit_price);
    $totalCost = $servicesTotal + $partsTotal;

    $mechanicPct = $mechanicPct ?? 0;
    $discountedServiceTotal = max(0, $servicesTotal - $record->discount); // new logic
    $mechAmount = round($discountedServiceTotal * $mechanicPct / 100, 2); // updated
@endphp

<div style="font-family: Arial, sans-serif; direction: rtl; color:#000; font-size:14px; padding:20px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; border-bottom:2px solid #3e2f92; padding-bottom:10px; margin-bottom:20px;">
        <img src="{{ asset('storage/logo.jpg') }}" alt="logo" style="width:90px; height:auto; margin-left:25px;"> {{-- ✅ Maximized --}}
        <div>
            <h2 style="margin:0; color:#3e2f92;">شركة أقساط لبيع السيارات</h2>
            <p style="margin:0;">مركز الصيانة فرع جنزور</p>
        </div>
    </div>

    {{-- Title --}}
    <h3 style="text-align:center; margin:0 0 15px; background:#c9a15d; color:#fff; padding:6px;">فاتورة فني الصيانة</h3>

    {{-- Basic Info --}}
    <table style="width:100%; border-collapse:collapse; margin-bottom:15px;">
        <tr>
            <td style="padding:5px;"><strong>رقم الطلب:</strong></td>
            <td style="padding:5px;">{{ $record->id }}</td>
            <td style="padding:5px;"><strong>التاريخ:</strong></td>
            <td style="padding:5px;">{{ $record->service_date }}</td>
        </tr>
        <tr>
            <td style="padding:5px;"><strong>الفني:</strong></td>
            <td style="padding:5px;">{{ $record->mechanic->name }}</td>
            <td style="padding:5px;"><strong>النسبة المتفق عليها:</strong></td>
            <td style="padding:5px;">{{ $mechanicPct }}%</td>
        </tr>
    </table>

    {{-- Services --}}
    <div style="background:#c9a15d; color:#fff; padding:6px; font-weight:bold;">الخدمات المنفذة</div>
    <table style="width:100%; border-collapse:collapse;" border="1">
        <thead>
            <tr style="background:#f8e4b8;">
                <th style="padding:6px;">الخدمة</th>
                <th style="padding:6px;">السعر</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($record->services as $srv)
                <tr>
                    <td style="padding:6px;">{{ $srv->serviceType->name }}</td>
                    <td style="padding:6px;">{{ number_format($srv->price,2) }} د.ل</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Parts --}}
    <!-- <div style="background:#c9a15d; color:#fff; padding:6px; font-weight:bold; margin-top:20px;">قطع الغيار المستخدمة</div>
    <table style="width:100%; border-collapse:collapse;" border="1">
        <thead>
            <tr style="background:#f8e4b8;">
                <th style="padding:6px;">القطعة</th>
                <th style="padding:6px;">العدد</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($record->partUsages as $u)
                <tr>
                    <td style="padding:6px;">{{ $u->part->name }}</td>
                    <td style="padding:6px;">{{ $u->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table> -->

    {{-- Summary --}}
    <table style="width:100%; border-collapse:collapse; margin-top:20px;">
        <tr>
            <td style="padding:5px;"><strong>إجمالي الخدمات:</strong></td>
            <td style="padding:5px;">{{ number_format($servicesTotal,2) }} د.ل</td>
        </tr>
        <tr>
            <td style="padding:5px;"><strong>الخصم:</strong></td>
            <td style="padding:5px;">{{ number_format($record->discount,2) }} د.ل</td>
        </tr>
        <tr>
            <td style="padding:5px;"><strong>إجمالي بعد الخصم:</strong></td>
            <td style="padding:5px;">{{ number_format($servicesTotal - $record->discount,2) }} د.ل</td>
        </tr>
        <tr>
            <td style="padding:5px;"><strong>مستحق الفني:</strong></td>
            <td style="padding:5px;">{{ $mechanicPct }}% = {{ number_format($mechAmount,2) }} د.ل</td>
        </tr>
    </table>

    {{-- Footer --}}
    <div style="border-top:1px solid #ccc; margin-top:35px; padding-top:10px; font-size:13px; color:#3e2f92; text-align:center; line-height:1.7;">
        <div>www.aqssat.ly</div>
        <div>cars@aqssat.ly</div>
        <div>الهـواتف: 0918043777 / 0928043777</div>
        <div style="color:#888;">{{ now()->format('Y-m-d H:i:s') }}</div>
    </div>

</div>
