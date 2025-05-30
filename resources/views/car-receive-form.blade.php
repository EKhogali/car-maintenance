<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>استمارة استلام سيارة</title>
</head>
<body style="font-family: Arial, sans-serif; direction: rtl; margin: 40px; color: #000; font-size: 14px;">

    <!-- Header -->
    <div style="display: flex; align-items: center; border-bottom: 2px solid #c9a15d; padding-bottom: 10px; margin-bottom: 20px;">
        <img src="{{ asset('images/logo.png') }}" style="width: 100px; margin-left: 20px;">
        <div>
            <h2 style="margin: 0;">مركز قراند لصيانة السيارات</h2>
            <p style="margin: 0;">فرع بنغازي - شارع سيدي خليفة</p>
        </div>
    </div>

    <!-- Car Info -->
    <div style="background: #c9a15d; color: white; padding: 8px; font-weight: bold;">استمارة فحص السيارة</div>
    <div style="margin-top: 10px;">
        <div><strong>اسم الزبون:</strong> {{ $car->customer->name }}</div>
        <div><strong>رقم الهاتف:</strong> {{ $car->customer->phone ?? '-' }}</div>
        <div><strong>رقم لوحة السيارة:</strong> {{ $car->license_plate }}</div>
        <div><strong>موديل السيارة:</strong> {{ $car->model }}</div>
        <div><strong>لون السيارة:</strong> {{ $car->color }}</div>
        <div><strong>العداد:</strong> {{ $car->mileage }} كم</div>
    </div>

    <!-- Check Items -->
    <div style="background: #c9a15d; color: white; padding: 8px; font-weight: bold; margin-top: 20px;">فحص الأليات / ميكانيك / سمكرة / طلاء / الصالة / أعطال</div>
    <div style="margin-top: 10px;">
        <label><input type="checkbox"> زيت المحرك</label><br>
        <label><input type="checkbox"> زيت الفرامل</label><br>
        <label><input type="checkbox"> زيت القير</label><br>
        <label><input type="checkbox"> فلتر الهواء</label><br>
        <label><input type="checkbox"> فحص شامل</label><br>
        <label><input type="checkbox"> فحص الهيكل</label><br>
        <label><input type="checkbox"> فحص الطلاء</label><br>
    </div>

    <!-- Notes -->
    <div style="background: #c9a15d; color: white; padding: 8px; font-weight: bold; margin-top: 20px;">ملاحظات أخرى</div>
    <div style="border: 1px solid #ccc; height: 100px; margin-top: 10px;"></div>

    <!-- Damage Map -->
    <div style="background: #c9a15d; color: white; padding: 8px; font-weight: bold; margin-top: 20px;">مواقع التلف</div>
    <img src="{{ asset('images/car-damage-map.png') }}" style="margin-top: 10px; width: 100%; max-width: 600px;">

    <!-- Signatures -->
    <div style="display: flex; justify-content: space-between; margin-top: 40px;">
        <div style="text-align: center;">
            توقيع الموظف:
            <br><br>____________________
        </div>
        <div style="text-align: center;">
            توقيع الزبون:
            <br><br>____________________
        </div>
    </div>

</body>
</html>
