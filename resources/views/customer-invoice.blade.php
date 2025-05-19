<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة صيانة</title>
    <script src="https://cdn.jsdelivr.net/npm/html2media@latest/dist/index.umd.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f9f9f9;
            padding: 20px;
        }
        .invoice {
            background: white;
            padding: 20px;
            border-radius: 12px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="invoice" id="invoice">
    <h2>فاتورة صيانة</h2>

    <div class="section">
        <p><span class="label">العميل:</span> {{ $record->car->customer->name }}</p>
        <p><span class="label">السيارة:</span> {{ $record->car->make }} - {{ $record->car->model }} - {{ $record->car->license_plate }}</p>
        <p><span class="label">التاريخ:</span> {{ $record->service_date }}</p>
    </div>

    <div class="section">
        <h4>الخدمات:</h4>
        <ul>
            @foreach ($record->serviceTypes as $service)
                <li>{{ $service->name }} - {{ number_format($service->price, 2) }} LYD</li>
            @endforeach
        </ul>
    </div>

    <div class="section">
        <p><span class="label">قراءة العداد:</span> {{ $record->odometer_reading }} كم</p>
        <p><span class="label">الميكانيكي:</span> {{ $record->mechanic->name ?? '-' }}</p>
        <p><span class="label">التكلفة:</span> {{ number_format($record->cost, 2) }} LYD</p>
    </div>
</div>

<div style="text-align: center; margin-top: 20px;">
    <button onclick="html2media({ selector: '#invoice', type: 'pdf', filename: 'invoice.pdf' })">
        طباعة / حفظ PDF
    </button>
</div>

</body>
</html>
