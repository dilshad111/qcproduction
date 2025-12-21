<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Card - {{ $jobCard->job_no }}</title>
    <!-- Use local bootstrap if available, or CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 5mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            width: 210mm;
            max-width: 100%;
            margin: 0 auto;
        }
        .print-container {
            border: 2px solid #000;
            padding: 5px;
            height: auto;
            min-height: 280mm; /* Near full A4 height */
            display: flex;
            flex-direction: column;
        }
        .header-grid {
            display: grid;
            grid-template-columns: 2fr 4fr 2fr;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }
        .logo-section {
            padding: 10px;
            border-right: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-section img {
            max-width: 120px;
            max-height: 80px;
        }
        .title-section {
            padding: 10px;
            text-align: center;
            border-right: 1px solid #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .title-section h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .meta-section {
            padding: 5px;
            font-size: 10px;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        .section-box {
            border: 1px solid #000;
            margin-bottom: 10px;
        }
        .section-title {
            background: #eee;
            font-weight: bold;
            padding: 2px 5px;
            border-bottom: 1px solid #000;
            text-align: center;
            text-transform: uppercase;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            padding: 5px;
        }
        .info-item {
            margin-bottom: 4px;
            display: flex;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
        }
        .info-value {
            flex: 1;
            border-bottom: 1px dotted #999;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .table-custom th, .table-custom td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
        }
        .technical-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .footer-sig {
            margin-top: auto;
            border-top: 1px solid #000;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            padding: 10px 50px;
        }
        .sig-box {
            text-align: center;
            border-top: 1px solid #000;
            width: 150px;
            padding-top: 5px;
        }
        /* Ply-based tolerance logic visual helper */
        .tolerance-info {
            font-size: 10px;
            font-style: italic;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="print-container">
        <!-- Header -->
        <div class="header-grid">
            <div class="logo-section">
                @if(isset($company) && $company->logo_path)
                    <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo">
                @else
                    <h3>{{ $company->name ?? 'COMPANY NAME' }}</h3>
                @endif
            </div>
            <div class="title-section">
                <h1>By Job Card</h1>
                <div>{{ $company->name ?? '' }}</div>
                <div style="font-size:10px;">{{ $company->address ?? '' }}</div>
            </div>
            <div class="meta-section">
                <div class="meta-row"><span>Job Card No.:</span> <strong>{{ $jobCard->job_no }}</strong></div>
                <div class="meta-row"><span>Date:</span> <span>{{ $jobCard->created_at->format('d-m-Y') }}</span></div>
                <div class="meta-row"><span>Doc No:</span> <span>PRD-FRM-01</span></div>
                <div class="meta-row"><span>Rev:</span> <span>02</span></div>
            </div>
        </div>

        <!-- Customer & Item Info -->
        <div class="section-box">
            <div class="section-title">Job Information</div>
            <div class="info-grid py-2">
                <div class="info-item">
                    <span class="info-label">Customer:</span>
                    <span class="info-value font-weight-bold">{{ $jobCard->customer->name ?? '' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Item Name:</span>
                    <span class="info-value font-weight-bold">{{ $jobCard->item_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Item Code:</span>
                    <span class="info-value">{{ $jobCard->item_code }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Carton Type:</span>
                    <span class="info-value">{{ $jobCard->cartonType->name ?? '' }} ({{ $jobCard->cartonType->standard_code ?? '' }})</span>
                </div>
            </div>
        </div>

        <!-- Technical Instructions Grid -->
        <div class="technical-grid">
            <!-- Left Column: Corrugation -->
            <div class="section-box">
                <div class="section-title">Instruction for Corrugation Plant</div>
                <div class="p-2">
                    <div class="info-item">
                        <span class="info-label">Size (Inner):</span>
                        <span class="info-value font-weight-bold">
                            {{ ($jobCard->uom == 'mm' ? number_format($jobCard->length, 0) : number_format($jobCard->length, 2)) }} x 
                            {{ ($jobCard->uom == 'mm' ? number_format($jobCard->width, 0) : number_format($jobCard->width, 2)) }} x 
                            {{ ($jobCard->uom == 'mm' ? number_format($jobCard->height, 0) : number_format($jobCard->height, 2)) }} 
                            {{ $jobCard->uom }}
                        </span>
                    </div>
                    <!-- Tolerances -->
                    @php
                        $tolerance = ($jobCard->ply_type == 5) ? 5 : 3;
                    @endphp
                    <div class="tolerance-info mb-2 text-end">Tolerance: +/- {{ $tolerance }} mm</div>

                    <div class="info-item">
                        <span class="info-label">Score Lines:</span>
                        {{-- Calculate Score Lines based on Height --}}
                        @php
                            $flap = $jobCard->width / 2;
                        @endphp
                        <span class="info-value">{{ number_format($flap, 1) }} - {{ number_format($jobCard->height, 1) }} - {{ number_format($flap, 1) }}</span>
                    </div>

                    <div class="info-item mt-2">
                        <span class="info-label">Deckle Size:</span>
                        <span class="info-value font-weight-bold">{{ $jobCard->deckle_size }} Inch</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Sheet Length:</span>
                        <span class="info-value font-weight-bold">{{ $jobCard->sheet_length }} Inch</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">UPS:</span>
                        <span class="info-value">{{ $jobCard->ups }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Ply Type:</span>
                        <span class="info-value font-weight-bold">{{ $jobCard->ply_type }}-Ply</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Slitting:</span>
                        <span class="info-value">{{ $jobCard->slitting_creasing }}</span>
                    </div>

                    <div class="mt-3">
                        <strong style="text-decoration: underline;">Paper Structure:</strong>
                        <table class="table-custom mt-1">
                            <thead>
                                <tr>
                                    <th>Layer</th>
                                    <th>Paper</th>
                                    <th>GSM</th>
                                    <th>Flute</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobCard->layers as $layer)
                                <tr>
                                    <td>{{ $layer->type }}</td>
                                    <td>{{ $layer->paper_name }}</td>
                                    <td>{{ $layer->gsm }}</td>
                                    <td>{{ $layer->flute_type }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Printing & Converting -->
            <div class="section-box">
                <div class="section-title">Printing & Finishing Instruction</div>
                <div class="p-2">
                     <div class="info-item">
                        <span class="info-label">Process Type:</span>
                        <span class="info-value font-weight-bold">{{ $jobCard->process_type }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Print Colors:</span>
                        <span class="info-value">{{ $jobCard->print_colors }} Colors</span>
                    </div>
                    
                    @if(!empty($jobCard->printing_data) && isset($jobCard->printing_data['inks']))
                        <div class="mt-2 mb-2">
                            <strong>Inks:</strong>
                            <ul style="margin: 0; padding-left: 20px;">
                            @foreach($jobCard->printing_data['inks'] as $inkId)
                                @php $ink = \App\Models\Ink::find($inkId); @endphp
                                <li>{{ $ink ? $ink->color_name . ' (' . $ink->color_code . ')' : 'Unknown' }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="info-item mt-3">
                        <span class="info-label">Pasting:</span>
                        <span class="info-value">{{ $jobCard->pasting_type }} {{ $jobCard->pasting_type == 'Staple' ? '('.$jobCard->staple_details.')' : '' }}</span>
                    </div>

                    @if(isset($jobCard->special_details['honeycomb']) || isset($jobCard->special_details['separator']))
                        <div class="mt-3">
                            <strong style="text-decoration: underline;">Special Add-ons:</strong>
                            @if(isset($jobCard->special_details['honeycomb']))
                                <div class="mt-1">
                                    <strong>Honeycomb:</strong> {{ $jobCard->special_details['honeycomb'] }}
                                </div>
                            @endif
                            @if(isset($jobCard->special_details['separator']))
                                <div class="mt-1">
                                    <strong>Separator:</strong> {{ $jobCard->special_details['separator'] }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="info-item mt-3">
                        <span class="info-label">Packing:</span>
                        <span class="info-value">{{ $jobCard->packing_bundle_qty }} Cartons / Bundle</span>
                    </div>

                    <div class="mt-3 p-2 bg-light border">
                        <strong>Reel Size Recommendation:</strong>
                        <br>
                        @php
                            // Logic: Deckle Size (mm) / 25.4 = Inch. Recommended Reel is Deckle Size.
                            $deckleInch = $jobCard->deckle_size / 25.4;
                        @endphp
                        Reel Size: {{ number_format($deckleInch, 1) }} Inch ({{ $jobCard->deckle_size }} mm)
                    </div>
                </div>
            </div>
        </div>

        <div class="section-box flex-grow-1">
            <div class="section-title">Remarks</div>
            <div class="p-2">
                {{ $jobCard->remarks }}
            </div>
        </div>

        <div class="footer-sig">
            <div class="sig-box">Prepared By</div>
            <div class="sig-box">Verified By</div>
            <div class="sig-box">Approved By</div>
        </div>
    </div>
</body>
</html>
