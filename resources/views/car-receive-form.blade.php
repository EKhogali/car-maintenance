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
            <h2 style="margin: 0;">مركز أقساط لصيانة السيارات</h2>
            <p style="margin: 0;">091111111111</p>
        </div>
    </div>

    <!-- Car Info -->
    <div style="background: #c9a15d; color: white; padding: 8px; font-weight: bold;">استمارة فحص السيارة</div>
    <!-- <div style="margin-top: 10px;">
        <div><strong>اسم الزبون:</strong> {{ $car->customer->name }}</div>
        <div><strong>رقم الهاتف:</strong> {{ $car->customer->phone ?? '-' }}</div>
        <div><strong>رقم لوحة السيارة:</strong> {{ $car->license_plate }}</div>
        <div><strong>موديل السيارة:</strong> {{ $car->model }}</div>
        <div><strong>لون السيارة:</strong> {{ $car->color }}</div>
        <div><strong>العداد:</strong> {{ $car->mileage }} كم</div>
    </div> -->

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px;">
        <tr>
            <td style="padding: 8px; border: 1px solid #000; width: 25%;"><strong>اسم الزبون:</strong></td>
            <td style="padding: 8px; border: 1px solid #000;">{{ $car->customer->name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #000;"><strong>رقم الهاتف:</strong></td>
            <td style="padding: 8px; border: 1px solid #000;">{{ $car->customer->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #000;"><strong>رقم لوحة السيارة:</strong></td>
            <td style="padding: 8px; border: 1px solid #000;">{{ $car->license_plate }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #000;"><strong>موديل السيارة:</strong></td>
            <td style="padding: 8px; border: 1px solid #000;">{{ $car->model }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #000;"><strong>لون السيارة:</strong></td>
            <td style="padding: 8px; border: 1px solid #000;">{{ $car->color }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #000;"><strong>العداد:</strong></td>
            <td style="padding: 8px; border: 1px solid #000;">{{ $car->mileage }} كم</td>
        </tr>
    </table>


    <div style="background: #c9a15d; color: white; padding: 8px; font-weight: bold; margin-top: 20px;">صور السيارة عند الاستلام</div>
    <div style="display: flex; gap: 10px;">
        @foreach($car->images as $image)
         <img src="{{ asset('storage/' . $image->image_path) }}"  width="300" height="200">
         @endforeach
    </div>

    <!-- Check Items -->
    <div style="background: #c9a15d; color: white; padding: 8px; font-weight: bold; margin-top: 20px;">الفحص الأولي</div>
    <div style="border: 1px solid #ccc; height: 100px; margin-top: 10px;">
        <div style="margin-top: 10px; min-height: 120px;">
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
        </div>
    </div>




    <!-- Notes -->
    <div style="background: #c9a15d; color: white; padding: 8px; font-weight: bold; margin-top: 20px;">ملاحظات أخرى</div>
    <div style="border: 1px solid #ccc; height: 100px; margin-top: 10px;">
        <div style="margin-top: 10px; min-height: 120px;">
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
            <div style="height: 20px;"></div>
        </div>
    </div>

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
