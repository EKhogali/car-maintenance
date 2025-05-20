<div style="text-align: right; direction: rtl; font-family: sans-serif;">
    <h4>الفحص الأولي:</h4>
    <p>{{ $record->first_check ?? '—' }}</p>

    <h4 style="margin-top: 15px;">الفحص التفصيلي:</h4>
    <p style="white-space: pre-wrap;">{{ $record->detailed_check ?? '—' }}</p>
</div>
