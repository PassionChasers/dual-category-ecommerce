@extends('layouts.admin.app')
@section('title', 'Admin | Medicine Details')

@push('styles')
    <style>
        /* Screen styles */
        .thumb-lg {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Make sure page looks nice on-screen */
        .print-card {
            --card-bg: #ffffff;
            --muted: #6b7280;
            background: var(--card-bg);
        }

        /* Hide print-only elements on screen */
        .print-only {
            display: none !important;
        }

        /* Print styles fallback inside same file (for users who print current page) */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                -webkit-font-smoothing: antialiased;
            }

            .no-print {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            .print-card {
                box-shadow: none !important;
                background: #fff !important;
            }

            /* A4 sizing and margins help ensure consistent PDF output */
            @page {
                size: A4;
                margin: 18mm;
            }

            html,
            body {
                height: auto;
                overflow: visible !important;
            }
        }
    </style>
@endpush

@section('contents')
    <div class="flex-1 overflow-auto bg-gray-50 p-4 md:p-6">
        <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Medicine Details</h1>
                <p class="text-gray-500 mt-1">Complete information for this medicine record</p>
            </div>

            <div class="flex items-center gap-2 no-print">
                <a href="{{ route('admin.medicines.index') }}"
                    class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 rounded hover:bg-gray-200 text-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                {{-- Print: opens print-only window with the card --}}
                <button id="printBtn"
                    class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                    <i class="fas fa-print"></i> Print
                </button>

                {{-- Export PDF (same behavior; users can save as PDF) --}}
                <button id="exportPdfBtn"
                    class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </div>
        </div>

        {{-- The card we will print/export — keep .print-card as selector used by JS --}}
        <div id="printCard" class="bg-white rounded-lg shadow overflow-hidden print-card">
            <div class="px-6 py-6 border-b flex flex-col md:flex-row items-start md:items-center md:justify-between gap-4">
                <div class="flex items-start gap-4">
                    {{-- image --}}
                    <div class="flex-shrink-0">
                        @if(!empty($medicine->ImageUrl))
                        <img id="cardImage" src="{{ asset('storage/'.$medicine->ImageUrl) }}" alt="{{ $medicine->Name }}"
                            class="thumb-lg">
                        @else
                        <div class="w-36 h-36 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                            No Image
                        </div>
                        @endif
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $medicine->Name ?? '-' }}</h2>
                        <div class="text-sm text-gray-600 mt-1">
                            {{ $medicine->BrandName ? e($medicine->BrandName) : ( $medicine->GenericName ?
                            e($medicine->GenericName) : '-' ) }}
                        </div>

                        <div class="mt-3 text-sm text-gray-700">
                            <span class="font-medium">Category:</span>
                            {{ optional($medicine->category)->Name ?? '-' }}
                            <span class="mx-2">•</span>
                            <span class="font-medium">Status:</span>
                            @if($medicine->IsActive) <span class="text-green-700 font-semibold">Active</span> @else <span
                                class="text-red-700 font-semibold">Inactive</span> @endif
                        </div>
                    </div>
                </div>

                <div class="ml-auto flex flex-col items-start md:items-end gap-2">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Price:</span> ৳ {{ isset($medicine->Price) ?
                        number_format($medicine->Price,2) : '-' }}
                    </div>
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">MRP:</span> {{ isset($medicine->MRP) ? '৳
                        '.number_format($medicine->MRP,2) : '-' }}
                    </div>
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Prescription required:</span> {{ $medicine->PrescriptionRequired ? 'Yes' :
                        'No' }}
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Description</h3>
                        <p class="mt-2 text-gray-700 whitespace-pre-line">{{ $medicine->Description ?? '-' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Manufacturer</h4>
                            <div class="mt-1 text-gray-700">{{ $medicine->Manufacturer ?? '-' }}</div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Dosage Form</h4>
                            <div class="mt-1 text-gray-700">{{ $medicine->DosageForm ?? '-' }}</div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Strength</h4>
                            <div class="mt-1 text-gray-700">{{ $medicine->Strength ?? '-' }}</div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Packaging</h4>
                            <div class="mt-1 text-gray-700">{{ $medicine->Packaging ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Metadata / summary --}}
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded p-4">
                        <h4 class="text-sm font-medium text-gray-700">Important Dates</h4>
                        <div class="mt-2 text-sm text-gray-700 space-y-1">
                            @php
                            use Illuminate\Support\Carbon;
                            $created = $medicine->CreatedAt ?? $medicine->created_at ?? null;
                            $updated = $medicine->UpdatedAt ?? $medicine->updated_at ?? null;
                            try { $createdFmt = $created ? Carbon::parse($created)->format('Y-m-d H:i') : '-'; }
                            catch(\Exception $e) { $createdFmt = '-'; }
                            try { $updatedFmt = $updated ? Carbon::parse($updated)->format('Y-m-d H:i') : '-'; }
                            catch(\Exception $e) { $updatedFmt = '-'; }
                            try { $expiryFmt = $medicine->ExpiryDate ? Carbon::parse($medicine->ExpiryDate)->format('Y-m-d')
                            : '-'; } catch(\Exception $e) { $expiryFmt = $medicine->ExpiryDate ?? '-'; }
                            @endphp

                            <div><span class="font-medium">Created:</span> {{ $createdFmt }}</div>
                            <div><span class="font-medium">Updated:</span> {{ $updatedFmt }}</div>
                            <div><span class="font-medium">Expiry:</span> {{ $expiryFmt }}</div>
                        </div>
                    </div>

                    {{-- Ratings & Reviews (placeholder data) --}}
                    <div class="bg-gray-50 rounded p-4">
                        <h4 class="text-sm font-medium text-gray-700">Ratings & Reviews</h4>
                        <div class="mt-2 text-sm text-gray-700">
                            <div><span class="font-medium">Average rating:</span> {{ $medicine->avg_rating ??
                                ($medicine->RatingsAvg ?? '-') }}</div>

                            <div class="mt-2"><span class="font-medium">Reviews:</span></div>
                            @if(!empty($medicine->reviews) && is_iterable($medicine->reviews))
                            <ul class="mt-2 space-y-2 max-h-40 overflow-y-auto">
                                @foreach($medicine->reviews as $r)
                                <li class="text-sm text-gray-700">
                                    <div class="font-medium">{{ $r->author ?? 'User' }} <span
                                            class="text-xs text-gray-500">— {{ $r->rating ?? '—' }}/5</span></div>
                                    <div class="text-gray-700">{{ \Illuminate\Support\Str::limit($r->comment ?? '-', 180) }}
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <div class="text-sm text-gray-500 mt-2">No reviews available.</div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded p-4 border">
                        <h4 class="text-sm font-medium text-gray-700">Quick Summary</h4>
                        <div class="mt-2 text-sm text-gray-700 space-y-1">
                            <div><strong>ID:</strong> {{ $medicine->MedicineId ?? '-' }}</div>
                            <div><strong>Category ID:</strong> {{ $medicine->MedicineCategoryId ?? '-' }}</div>
                            <div><strong>Prescription:</strong> {{ $medicine->PrescriptionRequired ? 'Yes' : 'No' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    @if(!empty($medicine->Notes))
                    <span class="font-medium">Notes:</span> {{ \Illuminate\Support\Str::limit($medicine->Notes, 120) }}
                    @endif
                </div>

                <div class="space-x-2 no-print">
                    <a href="{{ route('admin.medicines.index') }}"
                        class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">Back</a>
                    <button id="printBtnFooter"
                        class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Print</button>
                    <button id="exportPdfBtnFooter"
                        class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700">Export PDF</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /**
         * Print / Export helper:
         * - clones the element specified by selector (here '#printCard'),
         * - opens a popup window with full HTML including necessary styles,
         * - waits for images in the cloned content to load fully,
         * - triggers print (user chooses Save as PDF or actual printer).
         *
         * This approach is more reliable than printing the entire page and keeps your admin UI hidden.
         */
        (function () {
            const selector = '#printCard';

            // Collect CSS used for print window: inline styles from the page head + minimal reset + A4 rule
            function buildPrintStyles() {
                const base = `
                    <style>
                        html,body{margin:0;padding:0;font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;}
                        body { color:#111827; background: #fff; -webkit-print-color-adjust: exact; }
                        .thumb-lg { width:140px; height:140px; object-fit:cover; border-radius:8px; }
                        .title { font-size:20px; font-weight:700; margin-bottom:8px; }
                        .muted { color:#6b7280; }
                        .card { width:100%; max-width:820px; margin:0 auto; padding:20px; box-sizing:border-box; background:#fff; border-radius:6px; }
                        .row { display:flex; gap:18px; align-items:flex-start; }
                        .col { flex:1; }
                        .meta { font-size:14px; color:#374151; }
                        .muted-text { color:#6b7280; font-size:13px; }
                        .section { margin-top:16px; }
                        h4 { margin:0 0 6px 0; font-size:13px; color:#111827; }
                        p { margin:0; color:#374151; line-height:1.4; }
                        .chip { display:inline-block; padding:6px 10px; border-radius:999px; font-size:12px; border:1px solid #e5e7eb; background:#f9fafb; }
                        @page { size: A4; margin: 18mm; }
                    </style>
                `;
                return base;
            }

            function imagesLoaded(container) {
                const imgs = Array.from(container.querySelectorAll('img'));
                if (!imgs.length) return Promise.resolve();
                return Promise.all(imgs.map(img => {
                    if (img.complete) return Promise.resolve();
                    return new Promise(resolve => img.addEventListener('load', resolve, { once:true }));
                }));
            }

            function printElement(elemSelector) {
                const el = document.querySelector(elemSelector);
                if (!el) {
                    alert('Could not find printable content.');
                    return;
                }

                // Clone the element so we don't change original
                const clone = el.cloneNode(true);

                // Convert relative image src to absolute (helps popup access images)
                clone.querySelectorAll('img').forEach(img => {
                    const src = img.getAttribute('src') || '';
                    if (src && !src.startsWith('http') && !src.startsWith('data:')) {
                        // build absolute URL based on current location
                        const a = document.createElement('a'); a.href = src;
                        img.src = a.href;
                    }
                });

<<<<<<< HEAD
                // Build HTML for popup
                const html = `
                    <!doctype html>
                    <html>
                    <head>
                        <meta charset="utf-8"/>
                        <title>Print - Medicine</title>
                        ${buildPrintStyles()}
                    </head>
                    <body>
                        <div class="card">
                            ${clone.innerHTML}
                        </div>
                    </body>
                    </html>
                `;
=======
        // Wait until images in popup have loaded, then print
        const checkAndPrint = () => {
            const imgs = Array.from(popupDoc.querySelectorAll('img'));
            let promises = imgs.map(img => {
                if (img.complete) return Promise.resolve();
                return new Promise(resolve => img.addEventListener('load', resolve, { once:true }));
            });
            Promise.all(promises).then(() => {
                // Small timeout to let layout settle
                setTimeout(() => {
                    w.focus();
                    w.print();
                   
                }, 250);
            }).catch(() => {
                setTimeout(() => { w.focus(); w.print(); }, 300);
            });
        };
>>>>>>> c0fc83ddb31d95b5044bff30f32d0e4e962de7ca

                // Open popup window
                const w = window.open('', '_blank', 'width=900,height=1100,scrollbars=yes');
                if (!w) {
                    alert('Pop-up blocked. Allow pop-ups for this site to print/export.');
                    return;
                }

                w.document.open();
                w.document.write(html);
                w.document.close();

                // Wait until images inside popup are loaded
                // We need to reference images inside popup's document
                const popupDoc = w.document;
                const popupCard = popupDoc.querySelector('.card');

                // Wait until images in popup have loaded, then print
                const checkAndPrint = () => {
                    const imgs = Array.from(popupDoc.querySelectorAll('img'));
                    let promises = imgs.map(img => {
                        if (img.complete) return Promise.resolve();
                        return new Promise(resolve => img.addEventListener('load', resolve, { once:true }));
                    });
                    Promise.all(promises).then(() => {
                        // Small timeout to let layout settle
                        setTimeout(() => {
                            w.focus();
                            w.print();
                            // Optionally close the popup after printing (commented out to let users inspect)
                            // w.close();
                        }, 250);
                    }).catch(() => {
                        setTimeout(() => { w.focus(); w.print(); }, 300);
                    });
                };

                // If popup is still constructing, wait a bit then call check
                if (popupDoc.readyState === 'complete' || popupDoc.readyState === 'interactive') {
                    checkAndPrint();
                } else {
                    popupDoc.addEventListener('readystatechange', () => {
                        if (popupDoc.readyState === 'complete' || popupDoc.readyState === 'interactive') {
                            checkAndPrint();
                        }
                    });
                }
            }

            // Buttons
            document.getElementById('printBtn')?.addEventListener('click', function (e) {
                e.preventDefault();
                printElement(selector);
            });
            document.getElementById('exportPdfBtn')?.addEventListener('click', function (e) {
                e.preventDefault();
                printElement(selector);
            });
            document.getElementById('printBtnFooter')?.addEventListener('click', function (e) {
                e.preventDefault();
                printElement(selector);
            });
            document.getElementById('exportPdfBtnFooter')?.addEventListener('click', function (e) {
                e.preventDefault();
                printElement(selector);
            });

        })();
    </script>
@endpush