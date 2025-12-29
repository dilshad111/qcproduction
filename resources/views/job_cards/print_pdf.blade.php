<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Job Card - {{ $jobCard->job_no }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9pt;
            color: #000;
            line-height: 1.2;
        }
        .container {
            border: 2px solid #000;
            padding: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table {
            border: 2px solid #000;
            margin-bottom: 2px;
        }
        .header-table td {
            padding: 3px;
            border: 1px solid #000;
        }
        .logo-cell {
            width: 10%;
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
        }
        .title-cell {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            color: #0000CD;
        }
        .section-box {
            border: 1px solid #000;
            margin-bottom: 2px;
        }
        .section-title {
            background: #ddd;
            font-weight: bold;
            padding: 2px 4px;
            border-bottom: 2px solid #000;
            text-align: center;
            font-size: 10pt;
        }
        .content-table {
            width: 100%;
            font-size: 8pt;
        }
        .content-table td {
            padding: 2px;
            border: 1px solid #000;
        }
        .content-table th {
            padding: 2px;
            border: 1px solid #000;
            background: #f0f0f0;
            font-weight: bold;
        }
        .label {
            font-weight: bold;
            width: 30%;
        }
        .value {
            width: 70%;
        }
        .text-center {
            text-align: center;
        }
        .text-bold {
            font-weight: bold;
        }
        .footer-sig {
            margin-top: 3px;
            border-top: 1px solid #000;
            padding-top: 2px;
        }
        .footer-sig table {
            width: 100%;
        }
        .footer-sig td {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #000;
            font-size: 8pt;
        }
        .small-text {
            font-size: 7pt;
        }
        .info-box {
            padding: 3px;
            font-size: 8pt;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td rowspan="2" class="logo-cell">QC</td>
                <td colspan="8" class="title-cell">
                    Job Card
                    <div style="font-size: 8pt; font-weight: normal; float: right;">
                        <strong>Date:</strong> {{ now()->format('d/m/Y') }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-center small-text"><strong>Document ID</strong></td>
                <td class="text-center small-text"><strong>QC/DI3A/025</strong></td>
                <td class="text-center small-text"><strong>Rev. #</strong></td>
                <td class="text-center small-text text-bold">{{ str_pad($jobCard->version - 1, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="text-center small-text"><strong>Rev. Date</strong></td>
                <td class="text-center small-text"><strong>{{ $jobCard->version > 1 ? $jobCard->updated_at->format('d-m-Y') : '-' }}</strong></td>
                <td class="text-center small-text"><strong>Page #</strong></td>
                <td class="text-center small-text"><strong>1 of 1</strong></td>
            </tr>
        </table>

        <!-- Job Information -->
        <div class="section-box">
            <div class="section-title">JOB INFORMATION</div>
            <div class="info-box">
                <table style="width: 100%; border: 0;">
                    <tr>
                        <td style="border: 0; text-align: center; font-size: 11pt; font-weight: bold;">
                            {{ $jobCard->customer->name ?? '' }}
                        </td>
                        <td rowspan="3" style="border: 2px solid #000; width: 60px; text-align: center; vertical-align: middle; font-weight: bold; font-size: 9pt;">
                            {{ $jobCard->job_no }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 0; text-align: center; font-size: 9pt;">
                            <strong>Item:</strong> {{ $jobCard->item_name }} &nbsp;&nbsp;
                            <strong>Code:</strong> {{ $jobCard->item_code }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 0; text-align: center; font-size: 9pt;">
                            <strong>Size:</strong> 
                            {{ number_format($jobCard->length, 0) }} x 
                            {{ number_format($jobCard->width, 0) }} x 
                            {{ number_format($jobCard->height, 0) }} {{ $jobCard->uom }}
                            <em style="color: #c00;">(Tol: ±{{ $jobCard->ply_type == 5 ? 5 : 3 }}mm)</em>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Corrugation Plant Instructions -->
        <div class="section-box">
            <div class="section-title">INSTRUCTION FOR CORRUGATION PLANT</div>
            <div class="info-box">
                @if($jobCard->pieces_count > 1 && $jobCard->pieces->count() > 0)
                    @foreach($jobCard->pieces as $piece)
                        <div style="border: 1px solid #000; margin-bottom: 2px; padding: 2px;">
                            <div style="background: #e0e0e0; padding: 1px; text-align: center; font-weight: bold; font-size: 8pt;">
                                {{ $piece->piece_name ?: 'Piece ' . $piece->piece_number }}
                            </div>
                            <table class="content-table">
                                <tr>
                                    <td class="label">Size:</td>
                                    <td>{{ (int)$piece->length }} x {{ (int)$piece->width }} x {{ (int)$piece->height }} mm</td>
                                    <td class="label">Ply:</td>
                                    <td>{{ $piece->ply_type }}-Ply</td>
                                </tr>
                                <tr>
                                    <td class="label">Deckle Size:</td>
                                    <td>{{ number_format($piece->deckle_size, 0) }}"</td>
                                    <td class="label">Sheet Length:</td>
                                    <td>{{ number_format($piece->sheet_length, 2) }}" ({{ number_format($piece->sheet_length * 25.4, 0) }}mm)</td>
                                </tr>
                                <tr>
                                    <td class="label">UPS:</td>
                                    <td>{{ $piece->ups }}</td>
                                    <td class="label">Slitting:</td>
                                    <td>{{ $piece->slitting_creasing ?? 'N/A' }}</td>
                                </tr>
                            </table>
                            @if($piece->layers->count() > 0)
                                <table class="content-table" style="margin-top: 2px;">
                                    <tr>
                                        <th>L</th>
                                        <th>Paper</th>
                                        <th>GSM</th>
                                        <th>F</th>
                                    </tr>
                                    @foreach($piece->layers as $layer)
                                    <tr>
                                        <td class="text-center">{{ $layer->type }}</td>
                                        <td>{{ $layer->paper_name }}</td>
                                        <td class="text-center">{{ $layer->gsm }}</td>
                                        <td class="text-center">{{ $layer->flute_type ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            @endif
                            @if(!empty($piece->corrugation_instruction))
                                <div style="margin-top: 2px; padding: 2px; background: #ffffcc; border: 1px solid #000; font-size: 7pt;">
                                    <strong>⚠ Instruction:</strong> {{ $piece->corrugation_instruction }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <table class="content-table">
                        <tr>
                            <td class="label">Size:</td>
                            <td>{{ number_format($jobCard->length, 0) }} x {{ number_format($jobCard->width, 0) }} x {{ number_format($jobCard->height, 0) }} {{ $jobCard->uom }}</td>
                            <td class="label">Ply:</td>
                            <td>{{ $jobCard->ply_type }}-Ply</td>
                        </tr>
                        <tr>
                            <td class="label">Deckle Size:</td>
                            <td>{{ number_format($jobCard->deckle_size, 0) }}"</td>
                            <td class="label">Sheet Length:</td>
                            <td>{{ number_format($jobCard->sheet_length, 2) }}" ({{ number_format($jobCard->sheet_length * 25.4, 0) }}mm)</td>
                        </tr>
                        <tr>
                            <td class="label">UPS:</td>
                            <td>{{ $jobCard->ups }}</td>
                            <td class="label">Slitting:</td>
                            <td>{{ $jobCard->slitting_creasing }}</td>
                        </tr>
                    </table>
                    @if($jobCard->layers->count() > 0)
                        <table class="content-table" style="margin-top: 2px;">
                            <tr>
                                <th>L</th>
                                <th>Paper</th>
                                <th>GSM</th>
                                <th>F</th>
                            </tr>
                            @foreach($jobCard->layers as $layer)
                            <tr>
                                <td class="text-center">{{ $layer->type }}</td>
                                <td>{{ $layer->paper_name }}</td>
                                <td class="text-center">{{ $layer->gsm }}</td>
                                <td class="text-center">{{ $layer->flute_type ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </table>
                    @endif
                    @if(!empty($jobCard->corrugation_instruction))
                        <div style="margin-top: 2px; padding: 2px; background: #ffffcc; border: 1px solid #000; font-size: 7pt;">
                            <strong>⚠ Instruction:</strong> {{ $jobCard->corrugation_instruction }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Printing & Finishing -->
        <div class="section-box">
            <div class="section-title">PRINTING & FINISHING INSTRUCTION</div>
            <div class="info-box">
                <table class="content-table">
                    <tr>
                        <td class="label">Process Type:</td>
                        <td>{{ $jobCard->process_type }}</td>
                        <td class="label">Pasting:</td>
                        <td>{{ $jobCard->pasting_type }}</td>
                    </tr>
                    @if($jobCard->pieces_count <= 1)
                    <tr>
                        <td class="label">Printing:</td>
                        <td colspan="3">
                            @if($jobCard->print_colors == 0)
                                Un-Printed
                            @else
                                {{ $jobCard->print_colors }} Color Printing
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Packing:</td>
                        <td colspan="3">{{ $jobCard->packing_bundle_qty ?? 'N/A' }} / bundle @if($jobCard->packing_type)({{ $jobCard->packing_type }})@endif</td>
                    </tr>
                    @endif
                </table>
                
                @if($jobCard->pieces_count > 1)
                    @foreach($jobCard->pieces as $piece)
                        <div style="border: 1px solid #000; margin-top: 2px; padding: 2px;">
                            <strong style="font-size: 8pt;">{{ $piece->piece_name ?: 'Piece ' . $piece->piece_number }}:</strong>
                            Printing: {{ $piece->print_colors == 0 ? 'Un-Printed' : $piece->print_colors . ' Color' }}, 
                            Packing: {{ $piece->packing_bundle_qty ?? 'N/A' }}/bundle
                        </div>
                    @endforeach
                @endif

                @if(!empty($jobCard->printing_instruction))
                    <div style="margin-top: 2px; padding: 2px; background: #e7f3ff; border: 1px solid #000; font-size: 7pt;">
                        <strong>ℹ Printing:</strong> {{ $jobCard->printing_instruction }}
                    </div>
                @endif
                @if(!empty($jobCard->finishing_instruction))
                    <div style="margin-top: 2px; padding: 2px; background: #d4edda; border: 1px solid #000; font-size: 7pt;">
                        <strong>✂ Finishing:</strong> {{ $jobCard->finishing_instruction }}
                    </div>
                @endif
            </div>
        </div>

        <!-- General Remarks -->
        <div class="section-box">
            <div class="section-title">GENERAL REMARKS</div>
            <div class="info-box">
                {{ $jobCard->remarks ?: 'N/A' }}
            </div>
        </div>

        <!-- Signatures -->
        <div class="footer-sig">
            <table>
                <tr>
                    <td>Prepared</td>
                    <td>Verified</td>
                    <td>Approved</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
