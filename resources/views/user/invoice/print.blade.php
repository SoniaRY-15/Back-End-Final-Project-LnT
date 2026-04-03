<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        padding: 20px;
    }

    @media print {
        body {
            background: white;
        }

        .no-print {
            display: none;
        }
    }

    .invoice-header {
        border-bottom: 3px solid #007bff;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="row no-print mb-3">
            <div class="col-md-12">
                <button class="btn btn-primary" onclick="window.print()">🖨️ Print Invoice</button>
                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="invoice-header">
            <h1>📦 INVOICE</h1>
            <p class="mb-0"><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Date:</strong> {{ $invoice->created_at->format('d-m-Y H:i') }}</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Bill To:</h6>
                <p>
                    <strong>{{ $invoice->user->name }}</strong><br>
                    {{ $invoice->user->email }}<br>
                    {{ $invoice->user->phone }}
                </p>
            </div>
            <div class="col-md-6">
                <h6>Delivery To:</h6>
                <p>
                    {{ $invoice->address }}<br>
                    <strong>Postal Code:</strong> {{ $invoice->postal_code }}
                </p>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Product Name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($invoice->invoiceItems as $item)
                <tr>