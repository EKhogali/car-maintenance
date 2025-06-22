<div style="font-family: Arial, sans-serif; direction: rtl; margin: 40px; color: #000; font-size: 14px;">

    <!-- Header -->
    <div style="display: flex; align-items: center; border-bottom: 2px solid #3e2f92; padding-bottom: 10px; margin-bottom: 20px;">
        <img src="{{ asset('storage/logo.jpg') }}" alt="Aqssat Logo" style="width: 60px; height: auto; margin-left: 20px;">
        <div>
            <h2 style="margin: 0; color: #3e2f92;">شركة أقساط لبيع السيارات</h2>
            <p style="margin: 0;">مركز الصيانة فرع جنزور</p>
        </div>
    </div>

    <!-- Car Info Title -->
    <div style="background: #c9a15d; color: white; padding: 6px; font-weight: bold;">استمارة فحص السيارة</div>

    <!-- Car Details Table -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 12px;">
        <tr>
            <td style="padding: 4px; border: 1px solid #3e2f92; width: 25%;"><strong>اسم الزبون:</strong></td>
            <td style="padding: 4px; border: 1px solid #3e2f92;">{{ $car->customer->name }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #3e2f92;"><strong>رقم الهاتف:</strong></td>
            <td style="padding: 4px; border: 1px solid #3e2f92;">{{ $car->customer->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #3e2f92;"><strong>رقم لوحة السيارة:</strong></td>
            <td style="padding: 4px; border: 1px solid #3e2f92;">{{ $car->license_plate }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #3e2f92;"><strong>موديل السيارة:</strong></td>
            <td style="padding: 4px; border: 1px solid #3e2f92;">{{ $car->model }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #3e2f92;"><strong>لون السيارة:</strong></td>
            <td style="padding: 4px; border: 1px solid #3e2f92;">{{ $car->color }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #3e2f92;"><strong>العداد:</strong></td>
            <td style="padding: 4px; border: 1px solid #3e2f92;">{{ $car->mileage }} كم</td>
        </tr>
    </table>

    <!-- Car Image Section -->
    <div style="background: #c9a15d; color: white; padding: 6px; font-weight: bold; margin-top: 10px;">صور السيارة عند الاستلام</div>
    <div style="display: block; margin-top: 10px;">
        <img src="{{ asset('storage/car_sample.jpg') }}" style="width: 100%; max-width: 290px; height: auto; display: block;">
    </div>

    <!-- Initial Check -->
    <div style="background: #c9a15d; color: white; padding: 6px; font-weight: bold; margin-top: 10px;">الفحص الأولي</div>
    <div style="border: 1px solid #3e2f92; height: 80px; margin-top: 10px;"></div>

    <!-- Other Notes -->
    <div style="background: #c9a15d; color: white; padding: 6px; font-weight: bold; margin-top: 10px;">ملاحظات أخرى</div>
    <div style="border: 1px solid #3e2f92; height: 100px; margin-top: 10px;"></div>

    <!-- Spare Parts -->
    <div style="background: #c9a15d; color: white; padding: 4px 6px; font-weight: bold; margin-top: 10px;"><strong>قطع الغيار</strong></div>
    <div style="border: 1px solid #3e2f92; height: 100px; margin-top: 5px;"></div>

    <!-- Signatures -->
    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <div style="text-align: center;">
            توقيع الموظف:
            <br><br>____________________
        </div>
        <div style="text-align: center;">
            توقيع الزبون:
            <br><br>____________________
        </div>
    </div>

</div>
