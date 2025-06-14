<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>استمارة استلام سيارة</title>
    
</head>
<body style="font-family: Arial, sans-serif; direction: rtl; margin: 40px; color: #000; font-size: 14px;">

    <!-- Header -->
    <div style="display: flex; align-items: center; border-bottom: 1px solid #c9a15d; padding-bottom: 10px; margin-bottom: 20px;">
        <img src="{{ asset('storage/logo.jpg') }}" style="width: 60px; height: auto; margin-left: 20px; display: inline-block;">
        <div>
            <h2 style="margin: 0;">مركز أقساط لصيانة السيارات</h2>
            <p style="margin: 0;">091-804 3777</p>
        </div>
    </div>

    <!-- Car Info -->
    <div style="background: #c9a15d; color: white; padding: 4px 6px; font-weight: bold;">استمارة فحص السيارة</div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 12px;">
        <tr>
            <td style="padding: 4px; border: 1px solid #000; width: 25%;"><strong>اسم الزبون:</strong></td>
            <td style="padding: 4px; border: 1px solid #000;">{{ $car->customer->name }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #000;"><strong>رقم الهاتف:</strong></td>
            <td style="padding: 4px; border: 1px solid #000;">{{ $car->customer->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #000;"><strong>رقم لوحة السيارة:</strong></td>
            <td style="padding: 4px; border: 1px solid #000;">{{ $car->license_plate }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #000;"><strong>موديل السيارة:</strong></td>
            <td style="padding: 4px; border: 1px solid #000;">{{ $car->model }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #000;"><strong>لون السيارة:</strong></td>
            <td style="padding: 4px; border: 1px solid #000;">{{ $car->color }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #000;"><strong>العداد:</strong></td>
            <td style="padding: 4px; border: 1px solid #000;">{{ $car->mileage }} كم</td>
        </tr>
    </table>


    <div style="background: #c9a15d; color: white; padding: 4px 6px; font-weight: bold; margin-top: 20px;">صور السيارة عند الاستلام</div>


    <div style="display: block; margin-top: 10px;">
        <img src="{{ asset('storage/car_sample.jpg') }}" style="width: 100%; max-width: 290px; height: auto; display: block;">
    </div>



    <!-- Check Items -->
    <div style="background: #c9a15d; color: white; padding: 4px 6px; font-weight: bold; margin-top: 20px;">الفحص الأولي</div>
    <div style="border: 1px solid #ccc; height: 80px; margin-top: 10px;">
        <div style="margin-top: 10px; min-height: 120px;">
        </div>
    </div>




    <!-- Notes -->
    <div style="background: #c9a15d; color: white; padding: 4px 6px; font-weight: bold; margin-top: 20px;">ملاحظات أخرى</div>
    <div style="border: 1px solid #ccc; height: 100px; margin-top: 10px;">
        <div style="margin-top: 5px; min-height: 50px;">
        </div>
    </div>


    <div style="background: #c9a15d; color: white; padding: 4px 6px; font-weight: bold; margin-top: 10px;"><strong>قطع الغيار</strong></div>
    <div style="border: 1px solid #ccc; height: 100px; margin-top: 5px;">
        <div style="margin-top: 5px; min-height: 60px;">
        </div>
    </div>

    <!-- Signatures -->
    <div style="display: flex; justify-content: space-between; margin-top: 5px;">
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
