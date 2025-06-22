<div style="font-family: Arial, sans-serif; direction: rtl; color: #000; font-size: 14px; padding: 20px;">

<!-- Header -->
<div style="display: flex; align-items: center; border-bottom: 2px solid #3e2f92; padding-bottom: 10px; margin-bottom: 20px;">
    <img src="{{ asset('storage/logo.jpg') }}" alt="Aqssat Logo" style="width: 60px; height: auto; margin-left: 20px;">
    <div>
        <h2 style="margin: 0; color: #3e2f92;">شركة أقساط لبيع السيارات</h2>
        <p style="margin: 0;">مركز الصيانة فرع جنزور</p>
    </div>
</div>


    @php
        $cost = $record->cost;
        $supervisorPct = 10;
        $supervisorAmount = $cost * $supervisorPct / 100;

        $mechanicPct = $record->mechanic_pct ?? ($record->mechanic->work_pct ?? 0);
        $mechanicAmount = $cost * $mechanicPct / 100;

        // Extra 10% for each priced part
        $extraPartsAmount = $record->partUsages
            ->filter(fn($usage) => $usage->unit_price > 0)
            ->sum(fn($usage) => $usage->unit_price * $usage->quantity * 0.10);

        // Company gets remainder
        $companyAmount = ($cost - $supervisorAmount - $mechanicAmount) + $extraPartsAmount;
    @endphp

    <!-- Breakdown -->
    <div style="background: #f0f0f0; padding: 8px; font-weight: bold;">تفاصيل النسب والمبالغ</div>

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
                <td style="padding: 6px;">10%</td>
                <td style="padding: 6px;">{{ number_format($supervisorAmount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 6px;">هامش قطع الغيار (10% لكل قطعة سعرها > 0)</td>
                <td style="padding: 6px;">حسب كل قطعة</td>
                <td style="padding: 6px;">{{ number_format($extraPartsAmount, 2) }}</td>
            </tr>
            <tr style="font-weight: bold; background: #f5f5f5;">
                <td style="padding: 6px;">حصة الشركة</td>
                <td style="padding: 6px;">باقي المبلغ</td>
                <td style="padding: 6px;">{{ number_format($companyAmount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Summary -->
    <div style="background: #c9a15d; color: white; padding: 8px; margin-top: 20px; font-weight: bold;">الإجماليات</div>
    <table style="width: 100%; border-collapse: collapse;" border="1">
        <tr>
            <td style="padding: 6px;"><strong>الإجمالي:</strong></td>
            <td style="padding: 6px;">{{ number_format($cost, 2) }} د.ل</td>
        </tr>
        <tr>
            <td style="padding: 6px;"><strong>الخصم:</strong></td>
            <td style="padding: 6px;">{{ number_format($record->discount, 2) }} د.ل</td>
        </tr>
        <tr>
            <td style="padding: 6px;"><strong>المبلغ المستحق:</strong></td>
            <td style="padding: 6px;">{{ number_format($record->due, 2) }} د.ل</td>
        </tr>
    </table>

<!-- Footer -->
<div style="border-top: 1px solid #ccc; padding-top: 10px; font-size: 13px; text-align: center; margin-top: 40px; color: #3e2f92;">
    www.aqssat.ly - aqssatcar@gmail.com - الهواتف: 0918043777 / 0928043777
</div>

</div>
