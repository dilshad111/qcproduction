<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Issue - {{ $issue->jobCard->job_no }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
        }
        
        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }
        
        .header .logo {
            max-height: 60px;
            margin-bottom: 5px;
        }
        
        .header h1 {
            font-size: 20pt;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .header .company-info {
            font-size: 9pt;
            margin-top: 3px;
        }
        
        .doc-title {
            background: #000;
            color: #fff;
            text-align: center;
            padding: 8px;
            font-size: 14pt;
            font-weight: bold;
            margin: 10px 0;
        }
        
        /* Job Details Section */
        .job-details {
            border: 2px solid #000;
            margin-bottom: 10px;
        }
        
        .job-details table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .job-details th,
        .job-details td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        
        .job-details th {
            background: #e0e0e0;
            font-weight: bold;
            width: 25%;
        }
        
        /* Section Headers */
        .section-header {
            background: #333;
            color: #fff;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 12pt;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        
        /* Data Entry Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 8px 6px;
            text-align: center;
        }
        
        .data-table th {
            background: #d0d0d0;
            font-weight: bold;
            font-size: 10pt;
        }
        
        .data-table td {
            height: 25px;
        }
        
        .data-table .wide-cell {
            min-width: 80px;
        }
        
        /* Signature Section */
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            gap: 10px;
        }
        
        .signature-box {
            flex: 1;
            text-align: center;
            border: 1px solid #000;
            padding: 8px;
            min-height: 60px;
        }
        
        .signature-box .label {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 25px;
            display: block;
        }
        
        .signature-box .line {
            border-top: 1px solid #000;
            margin-top: 30px;
            padding-top: 3px;
            font-size: 8pt;
        }
        
        /* Print Styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            .page-break {
                page-break-after: always;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14pt;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        .info-text {
            font-size: 9pt;
            font-style: italic;
            color: #666;
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Print</button>
    
    <div class="container">
        <!-- Header -->
        <div class="header">
            @if(isset($company) && $company->logo_path)
                <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Company Logo" class="logo">
            @endif
            <h1>{{ isset($company) ? $company->name : 'CORRUGO MIS' }}</h1>
            <div class="company-info">
                @if(isset($company))
                    {{ $company->address ?? '' }} | Phone: {{ $company->phone ?? '' }} | Email: {{ $company->email ?? '' }}
                @endif
            </div>
        </div>
        
        <div class="doc-title">JOB ISSUE TO PRODUCTION</div>
        
        <!-- Job Details -->
        <div class="job-details">
            <table>
                <tr>
                    <th>Issue No.:</th>
                    <td><strong style="color: #d9534f;">{{ $issue->issue_no }}</strong></td>
                    <th>Issue Date:</th>
                    <td>{{ \Carbon\Carbon::parse($issue->created_at)->format('d-M-Y') }}</td>
                </tr>
                <tr>
                    <th>Job Card No:</th>
                    <td><strong>{{ $issue->jobCard->job_no }}</strong></td>
                    <th>PO Number:</th>
                    <td>{{ $issue->po_number }}</td>
                </tr>
                <tr>
                    <th>Customer:</th>
                    <td colspan="3"><strong>{{ $issue->customer->name }}</strong></td>
                </tr>
                <tr>
                    <th>Item Name:</th>
                    <td colspan="3">{{ $issue->jobCard->item_name }}</td>
                </tr>
                <tr>
                    <th>PO Number:</th>
                    <td>{{ $issue->po_number }}</td>
                    <th>Item Code:</th>
                    <td>{{ $issue->jobCard->item_code }}</td>
                </tr>
                <tr>
                    <th>Carton Type:</th>
                    <td>{{ $issue->jobCard->cartonType->name ?? 'N/A' }}</td>
                    <th>Ply:</th>
                    <td>{{ $issue->jobCard->ply_type }} Ply</td>
                </tr>
                <tr>
                    <th>Dimensions (L×W×H):</th>
                    <td colspan="3">
                        {{ $issue->jobCard->length }} × {{ $issue->jobCard->width }} × {{ $issue->jobCard->height }} {{ $issue->jobCard->uom }}
                    </td>
                </tr>
                <tr>
                    <th>Sheet Size:</th>
                    <td>{{ $issue->jobCard->sheet_length }} × {{ $issue->jobCard->sheet_width }} Inch</td>
                    <th>UPS:</th>
                    <td>{{ $issue->jobCard->ups }}</td>
                </tr>
                <tr>
                    <th>Order Quantity:</th>
                    <td><strong>{{ number_format($issue->order_qty_cartons) }} Cartons</strong></td>
                    <th>Required Sheets:</th>
                    <td><strong>{{ number_format($issue->required_sheet_qty) }} Sheets</strong></td>
                </tr>
            </table>
        </div>
        
        <!-- CORRUGATION SECTION -->
        <div class="section-header">1. CORRUGATION PLANT</div>
        <p class="info-text">Machine operator to fill in reel consumption and sheet production details</p>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2">Sr#</th>
                    <th colspan="3">Reel Consumption</th>
                    <th rowspan="2">Sheets Produced</th>
                    <th rowspan="2">Remarks</th>
                </tr>
                <tr>
                    <th>Reel Type</th>
                    <th>Reel Number</th>
                    <th>Weight (kg)</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= 15; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    <td class="wide-cell"></td>
                    <td class="wide-cell"></td>
                    <td class="wide-cell"></td>
                    <td class="wide-cell"></td>
                    <td></td>
                </tr>
                @endfor
            </tbody>
        </table>
        
        <div class="signature-row">
            <div class="signature-box">
                <span class="label">Shift In-Charge</span>
                <div class="line">Signature & Date</div>
            </div>
            <div class="signature-box">
                <span class="label">Line Clearance</span>
                <div class="line">Signature & Date</div>
            </div>
            <div class="signature-box">
                <span class="label">Machine Supervisor</span>
                <div class="line">Signature & Date</div>
            </div>
            <div class="signature-box">
                <span class="label">QC Personnel</span>
                <div class="line">Signature & Date</div>
            </div>
        </div>
        
        <!-- PRINTING & DIE CUTTING SECTION -->
        <div class="section-header">2. PRINTING & DIE CUTTING</div>
        <p class="info-text">Printing operator to fill in production details and quality checks</p>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Sr#</th>
                    <th>Date</th>
                    <th>Printing Quality</th>
                    <th>Size Check</th>
                    <th>Die Cutting Quality</th>
                    <th>Quantity Produced</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= 15; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    <td class="wide-cell"></td>
                    <td class="wide-cell"></td>
                    <td class="wide-cell"></td>
                    <td class="wide-cell"></td>
                    <td class="wide-cell"></td>
                    <td></td>
                </tr>
                @endfor
            </tbody>
        </table>
        
        <div class="signature-row">
            <div class="signature-box">
                <span class="label">Printing Operator</span>
                <div class="line">Signature & Date</div>
            </div>
            <div class="signature-box">
                <span class="label">Line Clearance</span>
                <div class="line">Signature & Date</div>
            </div>
            <div class="signature-box">
                <span class="label">Production Supervisor</span>
                <div class="line">Signature & Date</div>
            </div>
            <div class="signature-box">
                <span class="label">QC Personnel</span>
                <div class="line">Signature & Date</div>
            </div>
        </div>
        
        <!-- DISPATCH SECTION -->
        <div class="section-header">3. DISPATCH INFORMATION</div>
        <p class="info-text">Dispatcher to record all dispatch details</p>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Sr#</th>
                    <th>Date</th>
                    <th>Quantity In</th>
                    <th>D.C #</th>
                    <th>Dispatched Qty</th>
                    <th>Balance Qty</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= 20; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    <td class="wide-cell"></td>
                    <td class="wide-cell"></td>
                    <td class="wide-cell"></td>
                    <td class="wide-cell"></td>
                    <td class="wide-cell"></td>
                    <td></td>
                </tr>
                @endfor
            </tbody>
        </table>
        
        <div class="signature-row">
            <div class="signature-box" style="flex: 2;">
                <span class="label">Dispatcher</span>
                <div class="line">Signature, Name & Date</div>
            </div>
            <div class="signature-box" style="flex: 2;">
                <span class="label">Warehouse In-Charge</span>
                <div class="line">Signature, Name & Date</div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px; font-size: 9pt; color: #666;">
            <p>This is a computer generated document. Printed on: {{ date('d-M-Y h:i A') }}</p>
        </div>
    </div>
    
    <script>
        // Auto-print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
