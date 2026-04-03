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

        .invoice-total {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row no-print mb-3">
            <div class="col-md-12">
                <button class="btn btn-primary" onclick="window.print()">Print Invoice</button>
                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="invoice-header">
            <h1>INVOICE CHIPICHAPA</h1>
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

        <h6 class="mb-3">Order Details</h6>
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
                    <td>{{ $no++ }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>Rp. {{ number_format($item->product->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="invoice-total">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <h5><strong>Total Amount:</strong></h5>
                    <h4>Rp. {{ number_format($invoice->total_price, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="mt-5 text-center text-muted">
            <p>Thank you for your order!</p>
            <p>Printed on {{ now()->format('d-m-Y H:i') }}</p>
        </div>
    </div>
</body>

</html>