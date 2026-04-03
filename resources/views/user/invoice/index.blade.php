<!-- resources/views/user/invoice/index.blade.php -->
@extends('layouts.app')

@section('title', 'My Invoices')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Invoices</h5>
                <a href="{{ route('invoices.create') }}" class="btn btn-light btn-sm">+ Create Invoice</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice Number</th>
                                <th>Date</th>
                                <th>Address</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr>
                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                <td>{{ $invoice->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ Str::limit($invoice->address, 30) }}</td>
                                <td>Rp. {{ number_format($invoice->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-sm btn-secondary">Print</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No invoices yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection