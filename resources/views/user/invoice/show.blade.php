@extends('layouts.app')

@section('title', 'Invoice #' . $invoice->invoice_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">✅ Invoice Created Successfully</h5>
                <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-light btn-sm">🖨️ Print</a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Invoice Details</h6>
                        <p>
                            <strong>Invoice Number:</strong> {{ $invoice->invoice_number }}<br>
                            <strong>Date:</strong> {{ $invoice->created_at->format('d-m-Y H:i') }}<br>
                            <strong>Customer:</strong> {{ $invoice->user->name }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Delivery Address</h6>
                        <p>
                            {{ $invoice->address }}<br>
                            <strong>Postal Code:</strong> {{ $invoice->postal_code }}
                        </p>
                    </div>
                </div>

                <hr>

                <h6 class="mb-3">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->invoiceItems as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>Rp. {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-primary mt-3">
                    <h5 class="mb-0">Total: Rp. {{ number_format($invoice->total_price, 0, ',', '.') }}</h5>
                </div>

                <div class="mt-4">
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back to Invoices</a>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection