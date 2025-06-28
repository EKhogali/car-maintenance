<div style="font-family: Arial, sans-serif; direction: rtl; color: #000; font-size: 14px; padding: 20px;">

<!-- Header -->
<div style="display: flex; align-items: center; border-bottom: 2px solid #3e2f92; padding-bottom: 10px; margin-bottom: 20px;">
    <img src="{{ asset('storage/logo.jpg') }}" alt="Aqssat Logo" style="width: 60px; height: auto; margin-left: 20px;">
    <div>
        <h2 style="margin: 0; color: #3e2f92;">شركة أقساط لبيع السيارات</h2>
        <p style="margin: 0;">مركز الصيانة فرع جنزور</p>
    </div>
</div>

<!-- Title -->
<div style="background: #c9a15d; color: white; padding: 6px; font-weight: bold;">فاتورة صيانة</div>

<!-- Customer & Car Info -->
<table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
    <tr>
        <td style="padding: 5px;"><strong>الزبون:</strong></td>
        <td style="padding: 5px;">{{ $record->car->customer->name }}</td>
    </tr>
    <tr>
        <td style="padding: 5px;"><strong>السيارة:</strong></td>
        <td style="padding: 5px;">{{ $record->car->make }} - {{ $record->car->model }} -  <br> {{ $record->car->license_plate }}</td>
    </tr>
    <tr>
        <td style="padding: 5px;"><strong>تاريخ الخدمة:</strong></td>
        <td style="padding: 5px;">{{ $record->service_date }}</td>
    </tr>
</table>

<!-- Services Table -->
<div style="background: #c9a15d; color: white; padding: 6px; font-weight: bold; margin-top: 20px;">الخدمات المقدمة</div>
<table style="width: 100%; border-collapse: collapse;" border="1">
    <thead>
        <tr style="background: #f8e4b8;">
            <th style="padding: 6px;">الخدمة</th>
            <!-- <th style="padding: 6px;">السعر</th> -->
        </tr>
    </thead>
    <tbody>
        @php $serviceTotal = 0; @endphp
        @foreach ($record->serviceTypes as $service)
            @php $serviceTotal += $service->price; @endphp
            <tr>
                <td style="padding: 6px;">{{ $service->name }}</td>
                <!-- <td style="padding: 6px;">{{ number_format($service->price, 2) }} د.ل</td> -->
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Parts Table (With Price and Subtotal) -->
<div style="background: #c9a15d; color: white; padding: 6px; font-weight: bold; margin-top: 20px;">القطع المستخدمة</div>
<table style="width: 100%; border-collapse: collapse;" border="1">
    <thead>
        <tr style="background: #f8e4b8;">
            <th style="padding: 6px; text-align: center; vertical-align: middle;">القطعة</th>
            <th style="padding: 6px; text-align: center; vertical-align: middle;">الكمية</th>
            <th style="padding: 6px; text-align: center; vertical-align: middle;">سعر الوحدة</th>
            <th style="padding: 6px; text-align: center; vertical-align: middle;">الإجمالي</th>
        </tr>
    </thead>
    <tbody>
        @php $partsTotal = 0; @endphp
        @foreach ($record->partUsages as $usage)
            @php
                $subtotal = $usage->unit_price * $usage->quantity;
                $partsTotal += $subtotal;
            @endphp
            <tr>
                <td style="padding: 6px; text-align: center; vertical-align: middle;">{{ $usage->part->name }}</td>
                <td style="padding: 6px; text-align: center; vertical-align: middle;">{{ $usage->quantity }}</td>
                <td style="padding: 6px; text-align: center; vertical-align: middle;">{{ number_format($usage->unit_price, 2) }}</td>
                <td style="padding: 6px; text-align: center; vertical-align: middle;">{{ number_format($subtotal, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


<!-- Totals -->
<div style="background: #f0f0f0; padding: 6px; font-weight: bold; margin-top: 20px;">الإجماليات</div>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">

    @if($record->advance_payment > 0)
        <tr>
            <td><strong>الدفعة المقدمة:</strong></td>
            <td>{{ number_format($record->advance_payment, 2) }} د.ل</td>
        </tr>
        <!-- @if($record->advance_payment_note)
            <tr>
                <td><strong>ملاحظة الدفع المقدم:</strong></td>
                <td>{{ $record->advance_payment_note }}</td>
            </tr>
        @endif -->
    @endif

    <tr>
        <td style="padding: 5px;"><strong>إجمالي الخدمات:</strong></td>
        <td style="padding: 5px;">{{ number_format($serviceTotal, 2) }} د.ل</td>
    </tr>
    <tr>
        <td style="padding: 5px;"><strong>إجمالي القطع:</strong></td>
        <td style="padding: 5px;">{{ number_format($partsTotal, 2) }} د.ل</td>
    </tr>
    <tr>
        <td style="padding: 5px;"><strong>الإجمالي:</strong></td>
        <td style="padding: 5px;">{{ number_format($serviceTotal + $partsTotal, 2) }} د.ل</td>
    </tr>
    <tr>
        <td style="padding: 5px;"><strong>الخصم:</strong></td>
        <td style="padding: 5px;">{{ number_format($record->discount, 2) }} د.ل</td>
    </tr>
    <tr>
        <td style="padding: 5px;"><strong>المبلغ المستحق:</strong></td>
        <td style="padding: 5px;">{{ number_format($record->due, 2) }} د.ل</td>
    </tr>
    <tr>
        <td style="padding: 5px;"><strong>الميكانيكي:</strong></td>
        <td style="padding: 5px;">{{ $record->mechanic->name ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding: 5px;"><strong>قراءة العداد:</strong></td>
        <td style="padding: 5px;">{{ $record->odometer_reading }} كم</td>
    </tr>
</table>

<!-- Footer -->
<div style="border-top: 1px solid #ccc; padding-top: 10px; font-size: 13px; text-align: center; margin-top: 40px; color: #3e2f92;">
    www.aqssat.ly - aqssatcar@gmail.com - الهواتف: 0918043777 / 0928043777
</div>

</div>
