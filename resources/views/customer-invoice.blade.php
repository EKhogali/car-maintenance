<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة صيانة</title>
</head>
<body class="font-sans text-sm" style="direction: rtl; font-family: sans-serif;">

<div class="max-w-3xl mx-auto p-6 border border-black" id="invoice">

    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold mb-2">شركة أقساط القابضة</h2>
        <h3 class="text-xl font-semibold">فاتورة صيانة</h3>
    </div>

    <!-- Customer & Car Info -->
    <div class="mb-6">
        <h4 class="text-lg font-bold border-b border-black mb-2 pb-1">معلومات العميل والمركبة</h4>
        <table class="w-full border-collapse">
            <tr>
                <td class="border p-2 font-semibold w-1/3">اسم العميل:</td>
                <td class="border p-2">{{ $record->car->customer->name }}</td>
            </tr>
            <tr>
                <td class="border p-2 font-semibold">السيارة:</td>
                <td class="border p-2">{{ $record->car->make }} - {{ $record->car->model }}</td>
            </tr>
            <tr>
                <td class="border p-2 font-semibold">رقم اللوحة:</td>
                <td class="border p-2">{{ $record->car->license_plate }}</td>
            </tr>
            <tr>
                <td class="border p-2 font-semibold">تاريخ الصيانة:</td>
                <td class="border p-2">{{ $record->service_date }}</td>
            </tr>
            <tr>
                <td class="border p-2 font-semibold">قراءة العداد:</td>
                <td class="border p-2">{{ $record->odometer_reading }} كم</td>
            </tr>
        </table>
    </div>

    <!-- Services -->
    <div class="mb-6">
        <h4 class="text-lg font-bold border-b border-black mb-2 pb-1">تفاصيل الخدمات</h4>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2 text-right">الخدمة</th>
                    <th class="border p-2 text-right">التكلفة (LYD)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($record->serviceTypes as $service)
                    <tr>
                        <td class="border p-2">{{ $service->name }}</td>
                        <td class="border p-2">{{ number_format($service->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Summary -->
    <div class="mb-6">
        <h4 class="text-lg font-bold border-b border-black mb-2 pb-1">الملخص</h4>
        <table class="w-full border-collapse">
            <tr>
                <td class="border p-2 font-semibold w-1/3">الميكانيكي المسؤول:</td>
                <td class="border p-2">{{ $record->mechanic->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="border p-2 font-semibold">إجمالي التكلفة:</td>
                <td class="border p-2">{{ number_format($record->cost, 2) }} LYD</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="text-center pt-4 border-t border-black">
        <p class="text-base">شكراً لاختياركم خدماتنا</p>
    </div>
</div>

</body>
</html>
