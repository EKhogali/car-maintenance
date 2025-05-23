<div class="invoice p-6 text-sm leading-relaxed">
    <h2 class="text-xl font-bold border-b pb-2 mb-4">فاتورة صيانة</h2>

    <div class="mb-4">
        <p><span class="font-semibold">العميل:</span> {{ $record->car->customer->name }}</p>
        <p><span class="font-semibold">السيارة:</span> {{ $record->car->make }} - {{ $record->car->model }} - {{ $record->car->license_plate }}</p>
        <p><span class="font-semibold">التاريخ:</span> {{ $record->service_date }}</p>
    </div>

    <div class="mb-4">
        <h3 class="font-bold border-b mb-2">الخدمات:</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="border p-2 text-right">الخدمة</th>
                    <th class="border p-2 text-right">السعر</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($record->serviceTypes as $service)
                    <tr>
                        <td class="border p-2">{{ $service->name }}</td>
                        <td class="border p-2">{{ number_format($service->price, 2) }} LYD</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-4">
        <h3 class="font-bold border-b mb-2">القطع المستخدمة:</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="border p-2 text-right">القطعة</th>
                    <th class="border p-2 text-right">الكمية</th>
                    <th class="border p-2 text-right">سعر الوحدة</th>
                    <th class="border p-2 text-right">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($record->partUsages as $usage)
                    <tr>
                        <td>{{ $usage->part->name }}</td>
                        <td>{{ $usage->quantity }}</td>
                        <td>{{ number_format($usage->unit_price, 2) }}</td>
                        <td>{{ number_format($usage->quantity * $usage->unit_price, 2) }}</td>
                    </tr>
                @endforeach
                <!-- @foreach ($record->partUsages as $usage)
                    <tr>
                        <td class="border p-2">{{ $usage->part->name }}</td>
                        <td class="border p-2">{{ $usage->quantity }}</td>
                        <td class="border p-2">{{ number_format($usage->unit_price, 2) }} </td>
                        <td class="border p-2">{{ number_format($usage->quantity * $usage->unit_price, 2) }} </td>
                    </tr>
                @endforeach -->
            </tbody>
        </table>
    </div>

<div class="mt-6 border-t pt-4">
    <p><span class="font-semibold">الإجمالي:</span> {{ number_format($record->cost, 2) }} </p>
    <p><span class="font-semibold">الخصم:</span> {{ number_format($record->discount, 2) }} </p>
    <p><span class="font-semibold">المبلغ المستحق:</span> {{ number_format($record->due, 2) }} </p>
    <p><span class="font-semibold">الميكانيكي:</span> {{ $record->mechanic->name ?? '-' }}</p>
    <p><span class="font-semibold">قراءة العداد:</span> {{ $record->odometer_reading }} كم</p>
</div>

</div>
