
@if($records->isEmpty())
    <p style="color:red;">لا توجد نتائج للفترة المحددة.</p>
@else
    {{-- your loop and report content --}}
@endif


@php
    // pre-calculate totals
    $totals      = ['services' => 0, 'parts' => 0];
    $records->each(function ($r) use (&$totals) {
        $totals['services'] += $r->services->sum('price');
        $totals['parts']    += $r->partUsages->sum(fn ($u) => $u->quantity * $u->unit_price);
    });
    $overall = $totals['services'] + $totals['parts'];
    $pct     = $mechanic->work_pct ?? 0;   // fallback to default
    $amount  = round($overall * $pct / 100, 2);
@endphp

<div style="font-family:Arial;direction:rtl;font-size:13px;color:#000;padding:20px;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;border-bottom:2px solid #3e2f92;padding-bottom:10px;margin-bottom:15px;">
        <img src="{{ asset('storage/logo.jpg') }}" style="width:55px;margin-left:18px;">
        <div>
            <h2 style="margin:0;color:#3e2f92;">شركة أقساط لبيع السيارات</h2>
            <p style="margin:0;">مركز الصيانة فرع جنزور</p>
        </div>
    </div>

    <h3 style="text-align:center;background:#c9a15d;color:#fff;padding:6px;margin:0 0 15px;">
        تقرير أعمال الفني
    </h3>

    <table style="width:100%;border-collapse:collapse;margin-bottom:15px;">
        <tr>
            <td><strong>الفني:</strong></td><td>{{ $mechanic->name }}</td>
            <td><strong>الفترة:</strong></td><td>{{ $from }} ⟶ {{ $to }}</td>
        </tr>
        <tr>
            <td><strong>نسبة الفني:</strong></td><td>{{ $pct }}%</td>
            <td><strong>عدد الطلبات:</strong></td><td>{{ $records->count() }}</td>
        </tr>
    </table>

    {{-- Table of records --}}
    <table style="width:100%;border-collapse:collapse;" border="1">
        <thead>
            <tr style="background:#f8e4b8;">
                <th style="padding:5px;">التاريخ</th>
                <th style="padding:5px;">السيارة / اللوحة</th>
                <th style="padding:5px;">قيمة الخدمات</th>
                <th style="padding:5px;">قيمة القطع</th>
                <th style="padding:5px;">الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $rec)
                @php
                    $srv = $rec->services->sum('price');
                    $prt = $rec->partUsages->sum(fn($u) => $u->quantity*$u->unit_price);
                @endphp
                <tr>
                    <td style="padding:5px;">{{ $rec->service_date }}</td>
                    <td style="padding:5px;">
                        {{ $rec->car->license_plate }}<br>
                        {{ $rec->car->make }} - {{ $rec->car->model }}
                    </td>
                    <td style="padding:5px;">{{ number_format($srv,2) }}</td>
                    <td style="padding:5px;">{{ number_format($prt,2) }}</td>
                    <td style="padding:5px;">{{ number_format($srv+$prt,2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#eee;font-weight:bold;">
                <td colspan="2" style="padding:6px;text-align:center;">الإجماليات</td>
                <td style="padding:6px;">{{ number_format($totals['services'],2) }}</td>
                <td style="padding:6px;">{{ number_format($totals['parts'],2) }}</td>
                <td style="padding:6px;">{{ number_format($overall,2) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- payout --}}
    <p style="margin-top:15px;"><strong>مستحق الفني ({{ $pct }}%):</strong>
        {{ number_format($amount,2) }} د.ل
    </p>

    {{-- Footer --}}
    <div style="border-top:1px solid #ccc;margin-top:30px;padding-top:10px;text-align:center;line-height:1.7;color:#3e2f92;">
        <div>www.aqssat.ly</div>
        <div>cars@aqssat.ly</div>
        <div>الهـواتف: 0918043777 / 0928043777</div>
        <div style="color:#888;">{{ now()->format('Y-m-d H:i:s') }}</div>
    </div>

</div>
@endif