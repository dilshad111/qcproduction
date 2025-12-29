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
            size: A4 portrait;
            margin: 3mm 5mm 3mm 10mm; /* top right bottom left */
        }
        body {
            font-family: 'Segoe UI', 'Calibri', 'Arial', sans-serif;
            font-size: 10pt;
            color: #000000;
            background: #fff;
            width: auto;
            max-width: 100%;
            margin: 0;
            padding: 0;
            line-height: 1.3;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .print-container {
            border: 2.5px solid #000;
            padding: 4px;
            height: auto;
            display: flex;
            flex-direction: column;
            position: relative;
            page-break-after: avoid;
        }
        .header-grid {
            display: grid;
            grid-template-columns: 2fr 4fr 2fr;
            border-bottom: 2px solid #000;
            margin-bottom: 2px;
        }
        .logo-section {
            padding: 5px;
            border-right: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
        }
        .logo-section img {
            max-width: 80px;
            max-height: 30px;
        }
        .title-section {
            padding: 5px;
            text-align: center;
            border-right: 1px solid #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .title-section h1 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
            color: #0000CD;
            letter-spacing: 0.5px;
        }
        .meta-section {
            padding: 3px;
            font-size: 8pt;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        .section-box {
            border: 1.5px solid #000;
            margin-bottom: 1.5px;
            page-break-inside: avoid;
        }
        .section-title {
            background: linear-gradient(to bottom, #4d4d4d 0%, #000000 100%);
            color: #fff !important;
            font-weight: bold;
            padding: 3px 12px;
            border-radius: 4px;
            text-align: left;
            text-transform: uppercase;
            font-size: 10.5pt;
            letter-spacing: 0.5px;
            margin: 1px;
            display: block;
            border: 1px solid #000;
            -webkit-print-color-adjust: exact;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            padding: 1px 3px;
        }
        .info-item {
            margin-bottom: 1px;
            display: flex;
        }
        .info-label {
            font-weight: bold;
            width: 90px;
            font-size: 9pt;
            background: #f9f9f9;
            padding: 2px 4px;
        }
        .info-value {
            flex: 1;
            border-bottom: 1px dotted #999;
            font-size: 9pt;
            padding: 2px 4px;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }
        .table-custom th, .table-custom td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
        }
        .table-custom th {
            background: linear-gradient(135deg, #e8e8e8 0%, #d0d0d0 100%);
            font-weight: bold;
        }
        .technical-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px;
        }
        .footer-sig {
            margin-top: 2px;
            border-top: 1.5px solid #000;
            padding: 2px 30px 2px 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
            page-break-before: avoid;
        }
        .sig-box {
            text-align: center;
            border-top: 1.5px solid #000;
            width: 90px;
            padding-top: 3px;
            margin-top: 25px;
            font-size: 9pt;
            font-weight: 600;
        }
        .tolerance-info {
            font-size: 8pt;
            font-style: italic;
            font-weight: bold;
            color: #c00;
        }
        .mb-1 { margin-bottom: 2px !important; }
        .mb-2 { margin-bottom: 3px !important; }
        .mt-1 { margin-top: 2px !important; }
        .mt-2 { margin-top: 3px !important; }
        .p-1 { padding: 4px !important; }
        .p-2 { padding: 5px !important; }
    </style>
</head>
<body onload="window.print()">
    <div class="print-container">
        <!-- Header -->
        <div style="border: 2px solid #000; margin-bottom: 1px;">
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                <tr>
                    <!-- Logo Section -->
                    <td rowspan="2" style="width: 10%; border-right: 2px solid #000; padding: 4px; text-align: center; vertical-align: middle;">
                        @if(isset($company) && $company->logo_path)
                            @php
                                $logoSrc = isset($isPdf) && $isPdf 
                                    ? storage_path('app/public/' . $company->logo_path) 
                                    : asset('storage/' . $company->logo_path);
                            @endphp
                            <img src="{{ $logoSrc }}" alt="Logo" style="max-width: 70px; max-height: 50px;">
                        @else
                            <div style="font-weight: bold; font-size: 10px;">QC</div>
                        @endif
                    </td>
                    
                    <!-- First Row: Job Card Title and Printing Date -->
                    <td colspan="8" style="border-right: 2px solid #000; border-bottom: 2px solid #000; padding: 4px;">
                        <div style="display: flex; justify-content: center; align-items: center; position: relative;">
                            <h1 style="margin: 0; font-size: 16px; font-weight: bold; color: #0000CD; font-family: Arial, sans-serif;">Job Card</h1>
                            <div style="position: absolute; right: 0; text-align: right; font-size: 9px;">
                                <strong style="text-decoration: underline;">Printing Date:</strong> 
                                <span style="margin-left: 5px; font-weight: bold;">{{ now()->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <!-- Second Row: Document Details -->
                <tr>
                    <td style="border-right: 2px solid #000; padding: 2px; text-align: center; font-size: 8px; width: 11%; white-space: nowrap;">
                        <strong>Document ID No</strong>
                    </td>
                    <td style="border-right: 2px solid #000; padding: 2px; text-align: center; font-size: 8px; width: 11%; white-space: nowrap;">
                        <strong>QC/DI3A/025</strong>
                    </td>
                    <td style="border-right: 2px solid #000; padding: 2px; text-align: center; font-size: 8px; width: 8%; white-space: nowrap;">
                        <strong>Rev. #</strong>
                    </td>
                    <td style="border-right: 2px solid #000; padding: 2px; text-align: center; font-size: 9px; font-weight: bold; width: 6%; white-space: nowrap;">
                        {{ str_pad($jobCard->version - 1, 2, '0', STR_PAD_LEFT) }}
                    </td>
                    <td style="border-right: 2px solid #000; padding: 2px; text-align: center; font-size: 8px; width: 10%; white-space: nowrap;">
                        <strong>Rev. Date</strong>
                    </td>
                    <td style="border-right: 2px solid #000; padding: 2px; text-align: center; font-size: 8px; width: 10%; white-space: nowrap;">
                        <strong>{{ $jobCard->version > 1 ? $jobCard->updated_at->format('d-m-Y') : '-' }}</strong>
                    </td>
                    <td style="border-right: 2px solid #000; padding: 2px; text-align: center; font-size: 8px; width: 8%; white-space: nowrap;">
                        <strong>Page #</strong>
                    </td>
                    <td style="padding: 2px; text-align: center; font-size: 8px; width: 8%; white-space: nowrap;">
                        <strong>1 of 1</strong>
                    </td>
                </tr>
            </table>
        </div>


        <!-- Customer & Item Info -->
        <div class="section-box">
            <div class="section-title">1. Job Information</div>
            <div style="display: flex; padding: 5px; gap: 8px; align-items: center;">
                <!-- Left: Customer and Item Info (Centered) -->
                <div style="flex: 1; text-align: center;">
                    <!-- Row 1: Customer Name -->
                    <div style="margin-bottom: 5px;">
                        <span style="font-weight: bold; font-size: 14pt; background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%); padding: 3px 10px; border-radius: 3px;">{{ $jobCard->customer->name ?? '' }}</span>
                    </div>
                    
                    <!-- Row 2: Item Name and Item Code -->
                    <div style="font-size: 11pt; margin-bottom: 4px;">
                        <span style="font-weight: bold;">Item Name:</span>
                        <span style="font-weight: 600;">{{ $jobCard->item_name }}</span>
                        <span style="margin-left: 20px; font-weight: bold;">Item Code:</span>
                        <span style="font-weight: 600;">{{ $jobCard->item_code }}</span>
                    </div>
                    
                    <!-- Row 3: Item Size with Tolerance -->
                    @php
                        $tolerance = ($jobCard->ply_type == 5) ? 5 : 3;
                    @endphp
                    <div style="font-size: 11pt;">
                        <span style="font-weight: bold;">Size:</span>
                        <span style="font-weight: bold;">
                            {{ ($jobCard->uom == 'mm' ? number_format($jobCard->length, 0) : number_format($jobCard->length, 2)) }} x 
                            {{ ($jobCard->uom == 'mm' ? number_format($jobCard->width, 0) : number_format($jobCard->width, 2)) }} x 
                            {{ ($jobCard->uom == 'mm' ? number_format($jobCard->height, 0) : number_format($jobCard->height, 2)) }} 
                            {{ $jobCard->uom }}
                        </span>
                        <span style="margin-left: 10px; font-style: italic; color: #d9534f; font-weight: bold; font-size: 10pt;">
                            (Tolerance: ±{{ $tolerance }} mm)
                        </span>
                    </div>
                </div>
                
                <!-- Right: Job Card No Box + Design Preview (Side by Side) -->
                <div style="display: flex; gap: 6px; align-items: center;">
                    <!-- Job Card No Box (Left of Preview) -->
                    <div style="border: 2px solid #000; padding: 4px 6px; font-size: 10px; font-weight: 600; background: #fff; white-space: nowrap; writing-mode: vertical-rl; text-orientation: mixed; height: 0.7in; display: flex; align-items: center; justify-content: center;">
                        {{ $jobCard->job_no }}
                    </div>
                    
                    <!-- Carton Design Preview Box (0.7" x 0.7") -->
                    <div style="width: 0.7in; height: 0.7in; border: 2px solid #000; display: flex; align-items: center; justify-content: center; background: #f9f9f9;">
                        @php
                            $code = $jobCard->cartonType->standard_code ?? '';
                            if ($code) {
                                $exts = ['png', 'jpg', 'jpeg'];
                                $foundPath = null;
                                foreach ($exts as $ext) {
                                    if (file_exists(public_path("images/fefco/{$code}.{$ext}"))) {
                                        $foundPath = "images/fefco/{$code}.{$ext}";
                                        break;
                                    }
                                }
                                
                                $previewSrc = null;
                                if ($foundPath) {
                                    $previewSrc = isset($isPdf) && $isPdf ? public_path($foundPath) : asset($foundPath);
                                }
                            }
                        @endphp
                        @if($code && isset($previewSrc))
                            <img src="{{ $previewSrc }}" 
                                 style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        @else
                            <div style="text-align:center; font-size:8px; color:#666;">
                                {{ $jobCard->cartonType->name ?? 'Design' }}<br>{{ $code }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Instructions Grid - Stacked vertically as requested -->
        <div class="technical-grid" style="display: block;">
            <!-- Left Column: Corrugation -->
            <div class="section-box">
                <div class="section-title">2. Corrugation</div>
                <div class="p-0">
                    @if($jobCard->pieces_count > 1 && $jobCard->pieces->count() > 0)
                        <!-- Multi-Piece Layout - 2 Column Piece Grid -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px;">
                            @foreach($jobCard->pieces as $piece)
                                <div style="border: 1.5px solid #000; padding: 4px; background: #fafafa;">
                                    <div style="background: linear-gradient(135deg, #e0e0e0 0%, #d0d0d0 100%); padding: 2px; margin-bottom: 2px; font-weight: bold; text-align: center; font-size: 10pt;">
                                        {{ $piece->piece_name ?: 'Piece ' . $piece->piece_number }}
                                    </div>
                                    
                                    <!-- Size and Ply Header -->
                                    <div style="text-align: center; margin-bottom: 3px; font-size: 10pt;">
                                        <strong>Size:</strong> {{ (int)$piece->length }} x {{ (int)$piece->width }} x {{ (int)$piece->height }} mm &nbsp; <strong>Ply:</strong> {{ $piece->ply_type }}-Ply
                                    </div>
                                    
                                    <!-- Table for Deckle, Sheet Length, UPS -->
                                    <table style="width: 100%; border-collapse: collapse; font-size: 9pt; margin-bottom: 3px; border: 1.5px solid #000;">
                                        <thead>
                                            <tr style="background: linear-gradient(135deg, #e8e8e8 0%, #d0d0d0 100%);">
                                                <th style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;" rowspan="2">Deckle<br>Size</th>
                                                <th style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;" colspan="2">Sheet Length</th>
                                                <th style="border-bottom: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;" rowspan="2">UPS</th>
                                            </tr>
                                            <tr style="background: linear-gradient(135deg, #e8e8e8 0%, #d0d0d0 100%);">
                                                <th style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;">In Inches</th>
                                                <th style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;">In MM</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="border-right: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;">{{ number_format($piece->deckle_size, 0) }}"</td>
                                                <td style="border-right: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;">{{ number_format($piece->sheet_length, 2) }}"</td>
                                                <td style="border-right: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;">{{ number_format($piece->sheet_length * 25.4, 0) }} mm</td>
                                                <td style="padding: 3px; text-align: center; font-weight: bold;">{{ $piece->ups }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    @php
                                        $isRSCPlantPiece = (optional($jobCard->cartonType)->standard_code == '0201' && $piece->slitting_creasing == 'Plant Online');
                                        $p_flap1 = $p_height = $p_flap2 = 0;
                                        if ($isRSCPlantPiece) {
                                            if ($piece->ply_type == 3) {
                                                $p_flap1 = ($piece->width / 2) + 3;
                                                $p_height = $piece->height + 8;
                                                $p_flap2 = ($piece->width / 2) + 3;
                                            } elseif ($piece->ply_type == 5) {
                                                $p_flap1 = ($piece->width / 2) + 5;
                                                $p_height = $piece->height + 10;
                                                $p_flap2 = ($piece->width / 2) + 5;
                                            }
                                        }
                                    @endphp

                                    @if($isRSCPlantPiece && ($piece->ply_type == 3 || $piece->ply_type == 5))
                                        <div style="margin-bottom: 2px;">
                                            <div style="font-size: 9pt; margin-bottom: 2px;"><strong>Slitting:</strong> {{ $piece->slitting_creasing }}</div>
                                            <div style="text-align: center;">
                                                <table style="width: 80%; border-collapse: collapse; border: 2px solid #000; margin: 0 auto;">
                                                    <thead>
                                                        <tr style="background: linear-gradient(135deg, #e8e8e8 0%, #d0d0d0 100%);">
                                                            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-size: 10pt; font-weight: bold;">Flap # 1</th>
                                                            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-size: 10pt; font-weight: bold;">Height</th>
                                                            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-size: 10pt; font-weight: bold;">Flap # 2</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold; font-size: 11pt;">{{ number_format($p_flap1, 0) }}</td>
                                                            <td style="border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold; font-size: 11pt;">{{ number_format($p_height, 0) }}</td>
                                                            <td style="border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold; font-size: 11pt;">{{ number_format($p_flap2, 0) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @else
                                        <div style="font-size: 9pt; margin-bottom: 2px;">
                                            <strong>Slitting:</strong> {{ $piece->slitting_creasing ?? 'N/A' }} 
                                            @if(($piece->slitting_creasing ?? '') != 'None')
                                                &nbsp;&nbsp;
                                                <strong>Score:</strong> {{ number_format($piece->width/2, 0) }}-{{ number_format($piece->height, 0) }}-{{ number_format($piece->width/2, 0) }}
                                            @endif
                                        </div>
                                    @endif

                                    @if($piece->layers->count() > 0)
                                        <div class="mt-1">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                                                <strong style="font-size: 10pt;">Paper Structure:</strong>
                                            </div>
                                            <table style="width: 100%; border-collapse: collapse; margin-top: 2px; font-size: 9pt;">
                                                <thead>
                                                    <tr style="background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);">
                                                        <th style="border: 1px solid #999; padding: 3px; font-weight: bold; text-align: center;">Layer</th>
                                                        <th style="border: 1px solid #999; padding: 3px; font-weight: bold; text-align: center;">Paper</th>
                                                        <th style="border: 1px solid #999; padding: 3px; font-weight: bold; text-align: center;">GSM</th>
                                                        <th style="border: 1px solid #999; padding: 3px; font-weight: bold; text-align: center;">Flute</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($piece->layers as $layer)
                                                    <tr>
                                                        <td style="border: 1px solid #999; padding: 3px; text-align: left; font-weight: bold;">{{ $layer->type }}</td>
                                                        <td style="border: 1px solid #999; padding: 3px; text-align: left;">{{ $layer->paper_name }}</td>
                                                        <td style="border: 1px solid #999; padding: 3px; text-align: center;">{{ $layer->gsm }}</td>
                                                        <td style="border: 1px solid #999; padding: 3px; text-align: center;">{{ $layer->flute_type ?? '-' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                    @if(!empty($piece->corrugation_instruction))
                                    <div style="margin-top: 3px; padding: 3px; background: linear-gradient(135deg, #fffacd 0%, #fff9c4 100%); border: 1.5px solid #d4a017; border-radius: 2px;">
                                        <strong style="color: #856404; font-size: 9pt;">⚠ Plant Instruction:</strong>
                                        <div style="margin-top: 2px; font-size: 8pt; color: #000; font-weight: bold; background: #fff; padding: 3px; border: 1px dashed #ffc107;">{{ $piece->corrugation_instruction }}</div>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Single Piece Layout - Standard Compact -->
                        <div style="font-size: 11pt; margin-bottom: 4px; text-align: center;">
                            <span style="font-weight: bold;">Size (Inner):</span>
                            <span style="font-weight: bold;">
                                {{ ($jobCard->uom == 'mm' ? number_format($jobCard->length, 0) : number_format($jobCard->length, 2)) }} x 
                                {{ ($jobCard->uom == 'mm' ? number_format($jobCard->width, 0) : number_format($jobCard->width, 2)) }} x 
                                {{ ($jobCard->uom == 'mm' ? number_format($jobCard->height, 0) : number_format($jobCard->height, 2)) }} 
                                {{ $jobCard->uom }}
                            </span>
                            <span class="tolerance-info" style="font-size: 10pt; margin-left: 8px;">(Tol: {{ $jobCard->ply_type == 5 ? '+/- 5' : '+/- 3' }} mm)</span>
                            <span style="margin-left: 20px; font-weight: bold;">Ply Type:</span>
                            <span style="font-weight: bold;">{{ $jobCard->ply_type }}-Ply</span>
                        </div>

                        <!-- Technical Details Table -->
                        <table class="table-custom mt-2 mb-2">
                            <thead>
                                <tr style="background: #f0f0f0;">
                                    <th>Deckle Size</th>
                                    <th>Sheet Length (In)</th>
                                    <th>Sheet Length (MM)</th>
                                    <th>UPS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-weight: bold;">{{ number_format($jobCard->deckle_size, 0) }}"</td>
                                    <td style="font-weight: bold;">{{ number_format($jobCard->sheet_length, 2) }}"</td>
                                    <td style="font-weight: bold;">{{ number_format($jobCard->sheet_length * 25.4, 0) }} mm</td>
                                    <td style="font-weight: bold;">{{ $jobCard->ups }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="info-item">
                            <span class="info-label">Slitting:</span>
                            <span class="info-value">{{ $jobCard->slitting_creasing }}</span>
                        </div>

                        @php
                            $isRSCPlant = (optional($jobCard->cartonType)->standard_code == '0201' && $jobCard->slitting_creasing == 'Plant Online');
                            if ($isRSCPlant && ($jobCard->ply_type == 3 || $jobCard->ply_type == 5)) {
                                $s_flap1 = ($jobCard->width / 2) + ($jobCard->ply_type == 5 ? 5 : 3);
                                $s_height = $jobCard->height + ($jobCard->ply_type == 5 ? 10 : 8);
                                $s_flap2 = ($jobCard->width / 2) + ($jobCard->ply_type == 5 ? 5 : 3);
                            }
                        @endphp

                        @if($isRSCPlant && ($jobCard->ply_type == 3 || $jobCard->ply_type == 5))
                            <div style="text-align: center; margin-top: 6px;">
                                <table style="width: 80%; border-collapse: collapse; border: 2px solid #000; margin: 0 auto;">
                                    <thead>
                                        <tr style="background: linear-gradient(135deg, #e8e8e8 0%, #d0d0d0 100%);">
                                            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-size: 10pt; font-weight: bold;">Flap # 1</th>
                                            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-size: 10pt; font-weight: bold;">Height</th>
                                            <th style="border: 1px solid #000; padding: 4px; text-align: center; font-size: 10pt; font-weight: bold;">Flap # 2</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold; font-size: 11pt;">{{ number_format($s_flap1, 0) }}</td>
                                            <td style="border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold; font-size: 11pt;">{{ number_format($s_height, 0) }}</td>
                                            <td style="border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold; font-size: 11pt;">{{ number_format($s_flap2, 0) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="info-item">
                                <span class="info-label">Score Lines:</span>
                                @php $flap = $jobCard->width / 2; @endphp
                                <span class="info-value">{{ number_format($flap, 1) }} - {{ number_format($jobCard->height, 1) }} - {{ number_format($flap, 1) }}</span>
                            </div>
                        @endif

                        <!-- Paper Structure Table -->
                        <div style="margin-top: 8px;">
                            <strong style="font-size: 10pt;">Paper Structure:</strong>
                            <table class="table-custom mt-1">
                                <thead>
                                    <tr style="background: #f5f5f5;">
                                        <th>Layer</th>
                                        <th>Paper</th>
                                        <th>GSM</th>
                                        <th>Flute</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobCard->layers as $layer)
                                    <tr>
                                        <td style="text-align: left; padding-left: 5px; font-weight: bold;">{{ $layer->type }}</td>
                                        <td style="text-align: left; padding-left: 5px;">{{ $layer->paper_name }}</td>
                                        <td>{{ $layer->gsm }}</td>
                                        <td>{{ $layer->flute_type ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if(!empty($jobCard->corrugation_instruction))
                        <div style="margin-top: 3px; padding: 3px; background: linear-gradient(135deg, #fffacd 0%, #fff9c4 100%); border: 1.5px solid #d4a017; border-radius: 2px;">
                            <strong style="color: #856404; font-size: 9pt;">⚠ Plant Instruction:</strong>
                            <div style="margin-top: 2px; font-size: 8pt; color: #000; font-weight: bold; background: #fff; padding: 3px; border: 1px dashed #ffc107;">{{ $jobCard->corrugation_instruction }}</div>
                        </div>
                        @endif
                    @endif


                </div>
            </div>

            <!-- Right Column: Printing & Converting -->
            <div class="section-box">
                <div class="section-title">3. Printing & Finishing</div>
                <div class="p-0">
                    @if($jobCard->pieces_count > 1 && $jobCard->pieces->count() > 0)
                        <!-- Multi-Piece Printing Layout -->
                        <div style="display: flex; gap: 10px; margin-bottom: 2px; font-size: 10pt; justify-content: center;">
                            <div>
                                <span style="font-weight: bold;">Process:</span>
                                <span>{{ $jobCard->process_type }}</span>
                            </div>
                            <div>
                                <span style="font-weight: bold;">Pasting:</span>
                                <span>{{ $jobCard->pasting_type == 'None' ? 'No' : $jobCard->pasting_type }} {{ $jobCard->pasting_type == 'Staple' ? '('.$jobCard->staple_details.')' : '' }}</span>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2px; margin-top: 2px;">
                            @foreach($jobCard->pieces as $piece)
                                <div style="border: 1.5px solid #999; padding: 4px; background: #fafafa;">
                                    <div style="background: linear-gradient(135deg, #e8e8e8 0%, #d0d0d0 100%); padding: 3px; margin-bottom: 2px; font-weight: bold; text-align: center; font-size: 10pt;">
                                        {{ $piece->piece_name ?: 'Piece ' . $piece->piece_number }}
                                    </div>
                                    
                                    <div style="font-size: 9pt; text-align: center;">
                                        <div style="margin-bottom: 2px;">
                                            <span style="font-weight: bold;">Printing:</span>
                                            <span>
                                                @if($piece->print_colors == 0)
                                                    Un-Printed
                                                @else
                                                    {{ $piece->print_colors }} Color Printing
                                                @endif
                                            </span>
                                            @if(!empty($piece->printing_data) && isset($piece->printing_data['inks']) && count($piece->printing_data['inks']) > 0)
                                                &nbsp;|&nbsp;
                                                <span style="color: #c00; font-weight: bold;">
                                                @foreach($piece->printing_data['inks'] as $index => $inkId)
                                                    @php $ink = \App\Models\Ink::find($inkId); @endphp
                                                    {{ $ink ? $ink->color_name . ($ink->color_code ? ' ['.$ink->color_code.']' : '') : 'Ink' }}{{ $index < count($piece->printing_data['inks']) - 1 ? ', ' : '' }}
                                                @endforeach
                                                </span>
                                            @endif
                                        </div>
                                        <div style="margin-bottom: 2px;">
                                            <span style="font-weight: bold;">Packing:</span>
                                            <span>{{ $piece->packing_bundle_qty ?? 'N/A' }} / bundle {{ $piece->packing_type ? '('.$piece->packing_type.')' : '' }}</span>
                                        </div>
                                    </div>
                                    @if(!empty($piece->printing_instruction))
                                    <div style="margin-top: 4px; padding: 4px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: 1.5px solid #1976d2; border-radius: 3px;">
                                        <strong style="color: #004085; font-size: 11pt;">ℹ Print:</strong>
                                        <div style="margin-top: 3px; font-size: 10pt; color: #000; font-weight: bold; background: #fff; padding: 4px; border: 1px dashed #007bff;">{{ $piece->printing_instruction }}</div>
                                    </div>
                                    @endif

                                    @if(!empty($piece->finishing_instruction))
                                    <div style="margin-top: 4px; padding: 4px; background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border: 1.5px solid #28a745; border-radius: 3px;">
                                        <strong style="color: #155724; font-size: 11pt;">✂ Finish:</strong>
                                        <div style="margin-top: 3px; font-size: 10pt; color: #000; font-weight: bold; background: #fff; padding: 4px; border: 1px dashed #28a745;">{{ $piece->finishing_instruction }}</div>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Single Piece Printing Layout - Standard Compact -->
                        <div class="info-item">
                            <span class="info-label">Process Type:</span>
                            <span class="info-value">{{ $jobCard->process_type }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Pasting Type:</span>
                            <span class="info-value">{{ $jobCard->pasting_type }} {{ $jobCard->pasting_type == 'Staple' ? '('.$jobCard->staple_details.')' : '' }}</span>
                        </div>
                        
                        <div class="info-item mt-2">
                            <span class="info-label">Printing:</span>
                            <span class="info-value">
                                <span style="font-weight: bold;">
                                    @if($jobCard->print_colors == 0)
                                        Un-Printed
                                    @else
                                        {{ $jobCard->print_colors }} Color Printing
                                    @endif
                                </span>
                                @if(!empty($jobCard->printing_data) && isset($jobCard->printing_data['inks']) && count($jobCard->printing_data['inks']) > 0)
                                    <span style="color: #c00; font-weight: bold; margin-left: 10px;">
                                    @foreach($jobCard->printing_data['inks'] as $index => $inkId)
                                        @php $ink = \App\Models\Ink::find($inkId); @endphp
                                        {{ $ink ? $ink->color_name . ($ink->color_code ? ' ['.$ink->color_code.']' : '') : 'Ink' }}{{ $index < count($jobCard->printing_data['inks']) - 1 ? ', ' : '' }}
                                    @endforeach
                                    </span>
                                @endif
                            </span>
                        </div>

                        <div class="info-item mt-1">
                            <span class="info-label">Packing:</span>
                            <span class="info-value">
                                {{ $jobCard->packing_bundle_qty ?? 'N/A' }} / bundle 
                                @if($jobCard->packing_type) ({{ $jobCard->packing_type }}) @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Special Add-ons Block -->
            <div class="section-box mt-0">
                <div class="section-title">4. Special Add-ons</div>
                <div class="p-0">
                     <!-- Honeycomb -->
                     <div class="mb-1" style="font-size: 11pt; padding: 4px;">
                         <span style="font-weight: bold;">Honeycomb:</span>&nbsp;&nbsp;&nbsp;
                         @if(!empty($jobCard->special_details['honeycomb']) && !empty($jobCard->special_details['honeycomb']['holes']))
                             @php $h = $jobCard->special_details['honeycomb']; @endphp
                             {{ ($h['length'] ?? '-') }}x{{ ($h['width'] ?? '-') }}x{{ ($h['height'] ?? '-') }} {{ $h['uom'] ?? '' }}, 
                             {{ $h['plies'] ?? '-' }}-Ply, 
                             {{ $h['holes'] ?? '-' }} holes,
                             @if(!empty($h['material']))
                                 {{ $h['material'] }},
                             @endif
                             {{ ucfirst($h['source'] ?? '-') }}
                             @if(($h['source'] ?? '') == 'outsource') - {{ $h['supplier_name'] ?? 'N/A' }} @endif
                         @else
                             <span style="color: #000;">N/A</span>
                         @endif
                     </div>
                     
                     <!-- Separator -->
                     <div style="font-size: 11pt; padding: 4px;">
                         <span style="font-weight: bold;">Separator:</span>&nbsp;&nbsp;&nbsp;
                         @if(!empty($jobCard->special_details['separator']) && !empty($jobCard->special_details['separator']['length']))
                             @php $s = $jobCard->special_details['separator']; @endphp
                             {{ ($s['length'] ?? '-') }}x{{ ($s['width'] ?? '-') }} {{ $s['uom'] ?? '' }}, 
                             {{ $s['plies'] ?? '-' }}-Ply, 
                             {{ ucfirst($s['source'] ?? '-') }}
                             @if(($s['source'] ?? '') == 'outsource') - {{ $s['supplier_name'] ?? 'N/A' }} @endif
                         @else
                             <span style="color: #000;">N/A</span>
                         @endif
                     </div>
                </div>
            </div>

            <!-- Global Instructions (Only for Single Piece) -->
            @if($jobCard->pieces_count <= 1)
                <!-- Global Printing Instruction -->
                @if(!empty($jobCard->printing_instruction))
                <div style="margin-top: 5px; padding: 5px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: 1.5px solid #1976d2; border-radius: 3px;">
                    <strong style="color: #004085; font-size: 11pt;">ℹ Printing Instr:</strong>
                    <div style="margin-top: 3px; font-size: 10pt; color: #000; font-weight: bold; background: #fff; padding: 5px; border: 1px dashed #007bff;">{{ $jobCard->printing_instruction }}</div>
                </div>
                @endif
                
                <!-- Global Finishing Instruction -->
                @if(!empty($jobCard->finishing_instruction))
                <div style="margin-top: 5px; padding: 5px; background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border: 1.5px solid #28a745; border-radius: 3px;">
                    <strong style="color: #155724; font-size: 11pt;">✂ Finishing Instr:</strong>
                    <div style="margin-top: 3px; font-size: 10pt; color: #000; font-weight: bold; background: #fff; padding: 5px; border: 1px dashed #28a745;">{{ $jobCard->finishing_instruction }}</div>
                </div>
                @endif
            @endif
        </div>

        <div class="section-box" style="margin-top: 1px;">
            <div class="section-title">5. General Remarks</div>
            <div class="p-0" style="font-size: 9pt; font-weight: bold; padding: 2px;">
                {{ $jobCard->remarks ?: 'N/A' }}
            </div>
        </div>

        <div style="margin-top: auto;">
            <div class="footer-sig" style="border-top: 1px solid #000; padding: 1px 30px 2px 30px; margin-top: 2px;">
                <div class="sig-box">Prepared</div>
                <div class="sig-box">Verified</div>
                <div class="sig-box">Approved</div>
            </div>
        </div>
    </div>
</body>
</html>
