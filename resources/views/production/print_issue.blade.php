<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Issue - {{ $issue->issue_no }}</title>
    <style>
        @page {
            size: A4;
            margin: 3mm 3mm 3mm 10mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        
        .print-container {
            width: 100%;
            height: auto;
            position: relative;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 2px 4px;
            text-align: left;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Header Style */
        .header-table td {
            padding: 5px;
        }
        
        .doc-title-bar {
            background: #000;
            color: #fff;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            padding: 5px;
            margin: 2px 0;
        }
        
        .main-info-table th {
            background: #f2f2f2;
            width: 20%;
        }
        
        .piece-header {
            background: #f2f2f2;
            color: #d9534f;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }
        
        .section-header {
            background: #333;
            color: #fff;
            padding: 3px;
            font-weight: bold;
            font-size: 11px;
            margin-top: 3px;
            text-align: center;
        }.data-table th {
            background: #eee;
            font-size: 10px;
            text-align: center;
        }
        
        .data-table td {
            height: 20px;
        }
        
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            gap: 5px;
        }
        
        .signature-box {
            flex: 1;
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            min-height: 50px;
        }
        
        .signature-box .label {
            font-weight: bold;
            font-size: 10px;
            display: block;
            margin-bottom: 20px;
        }
        
        .signature-box .line {
            border-top: 1px solid #000;
            font-size: 9px;
            padding-top: 2px;
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .btn-print {
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Print Report</button>
    </div>

    <div class="print-container">
        <!-- Header Grid -->
        <table class="header-table" style="border: 2px solid #000;">
            <tr>
                <td rowspan="2" style="width: 12%; border-right: 2px solid #000; text-align: center;">
                    @if(isset($company) && $company->logo_path)
                        <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo" style="max-height: 50px; max-width: 100px;">
                    @else
                        <div style="font-weight: bold; font-size: 18px;">QC</div>
                    @endif
                </td>
                <td colspan="8" style="text-align: center; border-bottom: 2px solid #000;">
                    <h2 style="margin: 0; color: #0000CD; font-size: 18px;">2nd Process Manufacturing Report</h2>
                </td>
            </tr>
            <tr style="font-size: 10px;">
                <td style="border-right: 1px solid #000; font-weight: bold; width: 14%; vertical-align: middle; padding: 5px; text-align: center;">Document ID No</td>
                <td style="border-right: 1px solid #000; width: 14%; vertical-align: middle; padding: 5px; text-align: center;">QC/DI3A/051</td>
                <td style="border-right: 1px solid #000; font-weight: bold; width: 8%; vertical-align: middle; padding: 5px; text-align: center;">Rev. #</td>
                <td style="border-right: 1px solid #000; width: 8%; vertical-align: middle; padding: 5px; text-align: center;">0</td>
                <td style="border-right: 1px solid #000; font-weight: bold; width: 12%; vertical-align: middle; padding: 5px; text-align: center;">Rev. Date</td>
                <td style="border-right: 1px solid #000; width: 12%; vertical-align: middle; padding: 5px; text-align: center;">16/10/2016</td>
                <td style="border-right: 1px solid #000; font-weight: bold; width: 12%; white-space: nowrap; vertical-align: middle; padding: 5px; text-align: center;">Page #</td>
                <td style="width: 14%; white-space: nowrap; vertical-align: middle; padding: 5px; text-align: center;">1 of 1</td>
            </tr>
        </table>

        <div class="doc-title-bar">2nd PROCESS MANUFACTURING FOR CORRUGATED SHEET</div>

        <!-- Main Info Table -->
        <table class="main-info-table">
            <tr>
                <th class="font-bold">Issue No.:</th>
                <td style="color: #d9534f; font-weight: bold; font-size: 13px;">{{ $issue->issue_no }}</td>
                <th class="font-bold">Issue Date:</th>
                <td>{{ \Carbon\Carbon::parse($issue->created_at)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th class="font-bold">Cartons Quantity</th>
                <td class="font-bold">{{ number_format($issue->order_qty_cartons) }}</td>
                <th class="font-bold">PO Number:</th>
                <td>{{ $issue->po_number }}</td>
            </tr>
            <tr>
                <th class="font-bold">Customer & Item Name:</th>
                <td colspan="3">
                    <span class="font-bold">{{ $issue->customer->name }}</span>
                    <span style="margin: 0 10px;">|</span>
                    <span>{{ $issue->jobCard->item_name }}</span>
                </td>
            </tr>
            <tr>
                <th class="font-bold">Item Name & Code:</th>
                <td colspan="3">
                    <span>{{ $issue->jobCard->item_name }}</span>
                    <span style="margin: 0 10px;">|</span>
                    <span class="font-bold">{{ $issue->jobCard->item_code }}</span>
                </td>
            </tr>
        </table>

        <!-- Pieces Split Logic -->
        @if($issue->jobCard->pieces_count > 1)
            <div style="display: flex; border: 1px solid #000; margin-top: -1px;">
                @foreach($issue->jobCard->pieces as $index => $piece)
                    <div style="flex: 1; {{ $index == 0 ? 'border-right: 1px solid #000;' : '' }}">
                        <table>
                            <tr>
                                <td colspan="2" class="piece-header">{{ $piece->piece_name ?: 'Piece ' . ($index + 1) }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold" style="width: 40%;">Ply:</td>
                                <td class="text-center">{{ $piece->ply_type }}-Ply</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Dimensions (L×W×H):</td>
                                <td class="text-center">{{ (int)$piece->length }}x{{ (int)$piece->width }}x{{ (int)$piece->height }} mm</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Deckle Size inch</td>
                                <td class="text-center">{{ $piece->deckle_size }}"</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Sheet Length inch</td>
                                <td class="text-center">{{ $piece->sheet_length }}" | {{ round($piece->sheet_length * 25.4) }} mm</td>
                            </tr>
                            <tr>
                                <td class="font-bold">UPS</td>
                                <td class="text-center">{{ $piece->ups }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Construction:</td>
                                <td style="font-size: 9px; line-height: 1;">
                                    @foreach($piece->layers as $layer)
                                        {{ $layer->paper_name }} ({{ $layer->gsm }}){{ !$loop->last ? ' | ' : '' }}
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Carton Quantity</td>
                                <td class="text-center">{{ number_format($issue->order_qty_cartons) }} Cartons</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Sheet Quantity</td>
                                @php
                                    $pUps = $piece->ups > 0 ? $piece->ups : 1;
                                    $pSheets = ceil($issue->order_qty_cartons / $pUps);
                                @endphp
                                <td class="text-center">{{ number_format($pSheets) }} Sheets</td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Single Piece Layout -->
            <table>
                <tr>
                    <th class="font-bold">Ply:</th>
                    <td>{{ $issue->jobCard->ply_type }}-Ply</td>
                    <th class="font-bold">Dimensions (L×W×H):</th>
                    <td>{{ $issue->jobCard->length }}x{{ $issue->jobCard->width }}x{{ $issue->jobCard->height }} {{ $issue->jobCard->uom }}</td>
                </tr>
                <tr>
                    <th class="font-bold">Deckle Size:</th>
                    <td>{{ $issue->jobCard->deckle_size }} Inch</td>
                    <th class="font-bold">Sheet Length:</th>
                    <td>{{ $issue->jobCard->sheet_length }} Inch | {{ round($issue->jobCard->sheet_length * 25.4) }} mm</td>
                </tr>
                <tr>
                    <th class="font-bold">UPS:</th>
                    <td>{{ $issue->jobCard->ups }}</td>
                    <th class="font-bold">Required Sheets:</th>
                    <td class="font-bold">{{ number_format($issue->required_sheet_qty) }} Sheets</td>
                </tr>
                <tr>
                    <th class="font-bold">Construction:</th>
                    <td colspan="3" style="font-size: 10px;">
                        @foreach($issue->jobCard->layers as $layer)
                            {{ $layer->paper_name }} ({{ $layer->gsm }}) {{ $layer->flute_type ? '['.$layer->flute_type.']' : '' }}{{ !$loop->last ? ' | ' : '' }}
                        @endforeach
                    </td>
                </tr>
            </table>
        @endif

        <!-- Sections for completion -->
        @if($issue->jobCard->pieces_count > 1)
            <!-- Multi-piece: Show pieces side-by-side in panels -->
            <table class="data-table" style="margin-top: 10px; table-layout: fixed; width: 100%;">
                <thead>
                    <tr>
                        <th colspan="16" style="background: #333; color: #fff; text-align: center; padding: 4px; font-size: 10px;">CORRUGATION PLANT</th>
                    </tr>
                    <tr>
                        @foreach($issue->jobCard->pieces as $index => $piece)
                            <th colspan="8" style="background: #fff; text-align: center; padding: 3px; font-size: 9px; font-weight: bold; {{ $index == 0 ? 'border-right: 2px solid #000;' : '' }}">
                                {{ $piece->piece_name ?: 'Piece ' . ($index + 1) }}
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                {{ number_format($piece->deckle_size, 0) }}"
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                {{ number_format($piece->sheet_length, 2) }}"
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Date:_____________________
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($issue->jobCard->pieces as $index => $piece)
                            <th style="background: #000; color: #fff; width: 6.25%; padding: 2px; font-size: 8px;">Glue</th>
                            <th style="background: #fff; width: 6.25%; padding: 2px; font-size: 8px;">Starch</th>
                            <th style="background: #000; color: #fff; width: 6.25%; padding: 2px; font-size: 8px; line-height: 1.1;">Job Start<br>Time</th>
                            <th style="background: #fff; width: 6.25%; padding: 2px;"></th>
                            <th style="background: #000; color: #fff; width: 6.25%; padding: 2px; font-size: 8px; line-height: 1.1;">Job End<br>Time</th>
                            <th style="background: #fff; width: 6.25%; padding: 2px;"></th>
                            <th style="background: #000; color: #fff; width: 6.25%; padding: 2px; font-size: 8px; line-height: 1.1;">Sheet<br>Produced</th>
                            <th style="background: #fff; width: 6.25%; padding: 2px; {{ $index == 0 ? 'border-right: 2px solid #000;' : '' }}"></th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 16px;">
                        @foreach($issue->jobCard->pieces as $index => $piece)
                            <td style="font-weight: bold; background: #f2f2f2; padding: 2px; font-size: 8px;">Reel #</td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px; {{ $index == 0 ? 'border-right: 2px solid #000;' : '' }}"></td>
                        @endforeach
                    </tr>
                    <tr style="height: 16px;">
                        @foreach($issue->jobCard->pieces as $index => $piece)
                            <td style="font-weight: bold; background: #f2f2f2; padding: 2px; font-size: 8px;">Size</td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px; {{ $index == 0 ? 'border-right: 2px solid #000;' : '' }}"></td>
                        @endforeach
                    </tr>
                    <tr style="height: 16px;">
                        @foreach($issue->jobCard->pieces as $index => $piece)
                            <td style="font-weight: bold; background: #f2f2f2; padding: 2px; font-size: 8px;">Weight</td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px;"></td>
                            <td style="padding: 2px; {{ $index == 0 ? 'border-right: 2px solid #000;' : '' }}"></td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        @else
            <!-- Single piece: Show one table -->
            <table class="data-table" style="margin-top: 10px; table-layout: fixed; width: 100%;">
                <thead>
                    <tr>
                        <th colspan="11" style="background: #333; color: #fff; text-align: center; padding: 6px; font-size: 12px;">CORRUGATION PLANT</th>
                    </tr>
                    <tr>
                        <th style="background: #000; color: #fff; width: 9.09%;">Glue</th>
                        <th style="background: #fff; width: 9.09%;">Starch</th>
                        <th style="background: #000; color: #fff; width: 9.09%;" colspan="2">Job Start Time</th>
                        <th style="background: #fff; width: 9.09%;"></th>
                        <th style="background: #000; color: #fff; width: 9.09%;" colspan="2">Job End Time</th>
                        <th style="background: #fff; width: 9.09%;"></th>
                        <th style="background: #000; color: #fff; width: 9.09%;" colspan="2">Sheet Produced</th>
                        <th style="background: #fff; width: 9.09%;"></th>
                    </tr>
                </thead>
                <tbody>
                <tr style="height: 16px;">
                    <td style="font-weight: bold; background: #f2f2f2; padding: 2px; font-size: 8px;">Reel #</td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                </tr>
                <tr style="height: 16px;">
                    <td style="font-weight: bold; background: #f2f2f2; padding: 2px; font-size: 8px;">Size</td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                </tr>
                <tr style="height: 16px;">
                    <td style="font-weight: bold; background: #f2f2f2; padding: 2px; font-size: 8px; border-bottom: 3px double #000;">Weight</td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                </tr>
                <tr style="height: 16px;">
                    <td style="font-weight: bold; background: #f2f2f2; padding: 2px; font-size: 8px;">Reel #</td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                </tr>
                <tr style="height: 16px;">
                    <td style="font-weight: bold; background: #f2f2f2; padding: 2px; font-size: 8px;">Size</td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                </tr>
                <tr style="height: 16px;">
                    <td style="font-weight: bold; background: #f2f2f2; padding: 2px; font-size: 8px; border-bottom: 3px double #000;">Weight</td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                    <td style="padding: 2px; border-bottom: 3px double #000;"></td>
                </tr>
            </tbody>
            </table>
        @endif

        <div class="signature-row" style="margin-top: 5px; margin-bottom: 5px;">
            <div class="signature-box"><span class="label">Shift In-Charge</span><div class="line">Sig & Date</div></div>
            <div class="signature-box"><span class="label">Line Clearance</span><div class="line">Sig & Date</div></div>
            <div class="signature-box"><span class="label">Machine Supervisor</span><div class="line">Sig & Date</div></div>
            <div class="signature-box"><span class="label">QC Personnel</span><div class="line">Sig & Date</div></div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th colspan="8" style="background: #333; color: #fff; text-align: center; padding: 6px; font-size: 12px;">3rd PROCESS MANUFACTURING FOR CORRUGATED CARTONS</th>
                </tr>
                <tr>
                    <td colspan="8" style="padding: 4px; font-size: 10px;">
                        <span style="font-weight: bold;">Job Start Time & Date:</span>
                        <span style="border-bottom: 1px solid #000; display: inline-block; width: 150px; margin-left: 5px;"></span>
                        <span style="font-weight: bold; margin-left: 30px;">Job End Time & Date:</span>
                        <span style="border-bottom: 1px solid #000; display: inline-block; width: 150px; margin-left: 5px;"></span>
                    </td>
                </tr>
                <tr>
                    <th style="width: 40px;">S. No.</th>
                    <th style="width: 10%;">Cartons Size</th>
                    <th style="width: 10%;">Printing</th>
                    <th style="width: 10%;">Color Shade</th>
                    <th style="width: 10%;">Quantity of Box</th>
                    <th style="width: 8%;">Waste</th>
                    <th style="width: 10%;">Final Quantity</th>
                    <th style="width: 32%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= 2; $i++)
                <tr style="height: 18px;">
                    <td class="text-center" style="padding: 2px;">{{ $i }}</td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                </tr>
                @endfor
            </tbody>
        </table>

        <div class="signature-row" style="margin-top: 5px; margin-bottom: 5px;">
            <div class="signature-box"><span class="label">Operator</span><div class="line">Sig & Date</div></div>
            <div class="signature-box"><span class="label">Line Clearance</span><div class="line">Sig & Date</div></div>
            <div class="signature-box"><span class="label">Production Supervisor</span><div class="line">Sig & Date</div></div>
            <div class="signature-box"><span class="label">QC Personnel</span><div class="line">Sig & Date</div></div>
        </div>

        <table class="data-table" style="table-layout: fixed; width: 100%;">
            <thead>
                <tr>
                    <th colspan="12" style="background: #333; color: #fff; text-align: center; padding: 6px; font-size: 12px;">FINISHED GOODS DISPATCH</th>
                </tr>
                <tr>
                    <th style="width: 3%;">S. No.</th>
                    <th style="width: 13.67%;">Date</th>
                    <th style="width: 11.67%;">Qty. In</th>
                    <th style="width: 11.67%;">DC. No.</th>
                    <th style="width: 11.67%;">Qty. Out</th>
                    <th style="width: 11.67%; border-right: 2px solid #000;">Qty Balance</th>
                    <th style="width: 3%;">S. No.</th>
                    <th style="width: 13.67%;">Date</th>
                    <th style="width: 11.67%;">Qty. In</th>
                    <th style="width: 11.67%;">DC. No.</th>
                    <th style="width: 11.67%;">Qty. Out</th>
                    <th style="width: 11.67%;">Qty Balance</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $rowCount = $issue->jobCard->pieces_count > 1 ? 6 : 9;
                @endphp
                @for($i = 1; $i <= $rowCount; $i++)
                <tr style="height: 18px;">
                    <td class="text-center" style="padding: 2px;">{{ $i }}</td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="border-right: 2px solid #000; padding: 2px;"></td>
                    <td class="text-center" style="padding: 2px;">{{ $i + $rowCount }}</td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                </tr>
                @endfor
            </tbody>
        </table>

        <table class="data-table" style="margin-top: -1px;">
            <tr style="height: 18px;">
                <td style="font-weight: bold; width: 100px; padding: 4px; font-size: 9px;">Remarks:</td>
                <td style="padding: 4px;"></td>
            </tr>
        </table>

        <div style="text-align: center; margin-top: 40px; margin-bottom: 3px;">
            <div style="display: inline-block;">
                <span style="font-weight: bold; color: #000;">Dispatcher</span>
                <span style="border-bottom: 1px solid #000; display: inline-block; width: 200px; margin-left: 10px; padding-bottom: 2px; color: #999;">Sig & Date</span>
            </div>
        </div>


    </div>
</body>
</html>
