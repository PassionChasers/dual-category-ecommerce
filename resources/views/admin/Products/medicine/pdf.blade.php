{{-- Minimalist PDF layout — similar to show but inline styles help DOMPDF rendering --}}
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $medicine->Name ?? 'Medicine' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111; }
        .header { margin-bottom: 18px; }
        .two-col { display: flex; gap: 16px; }
        .img { width: 140px; height: 140px; object-fit: cover; }
        .box { border: 1px solid #e5e7eb; padding: 10px; border-radius: 6px; }
        .label { font-weight: 700; color: #374151; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $medicine->Name }}</h1>
        <div style="color:#555">{{ $medicine->BrandName ?: $medicine->GenericName }}</div>
    </div>

    <div class="two-col">
        <div>
            @if($medicine->ImageUrl)
                <img src="{{ public_path('storage/' . $medicine->ImageUrl) }}" class="img" alt="">
            @endif
        </div>

        <div style="flex:1">
            <div class="box">
                <div><span class="label">Price:</span> ৳ {{ number_format($medicine->Price ?? 0, 2) }}</div>
                <div><span class="label">MRP:</span> ৳ {{ number_format($medicine->MRP ?? 0, 2) }}</div>
                <div><span class="label">Category:</span> {{ optional($medicine->category)->Name }}</div>
                <div><span class="label">Prescription:</span> {{ $medicine->PrescriptionRequired ? 'Yes' : 'No' }}</div>
                <div style="margin-top:8px;"><span class="label">Manufacturer:</span> {{ $medicine->Manufacturer ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div style="margin-top:12px;">
        <h3>Description</h3>
        <div style="color:#333">{!! nl2br(e($medicine->Description ?? '-')) !!}</div>
    </div>

    <div style="margin-top:18px; color:#666; font-size:12px;">
        Created: {{ $medicine->CreatedAt ? \Carbon\Carbon::parse($medicine->CreatedAt)->toDayDateTimeString() : '-' }} |
        Updated: {{ $medicine->UpdatedAt ? \Carbon\Carbon::parse($medicine->UpdatedAt)->toDayDateTimeString() : '-' }}
    </div>
</body>
</html>
