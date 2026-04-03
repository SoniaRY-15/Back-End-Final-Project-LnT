@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2 class="mb-4">Create Invoice</h2>

        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf

            <!-- Items Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Invoice Items</h5>
                </div>
                <div class="card-body">
                    <div id="items-container">
                        <div class="item-row mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Product</label>
                                    <select name="items[0][product_id]" class="form-control" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                                                {{ $product->name }} (Stock: {{ $product->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Quantity</label>
                                    <input type="number" name="items[0][quantity]" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-danger w-100" onclick="removeItem(0)">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success mt-3" onclick="addItem()">+ Add Item</button>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Shipping Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">Create Invoice</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let itemCount = 1;

function addItem() {
    const container = document.getElementById('items-container');
    const itemRow = document.createElement('div');
    itemRow.className = 'item-row mb-3';
    itemRow.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <label>Product</label>
                <select name="items[${itemCount}][product_id]" class="form-control" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                            {{ $product->name }} (Stock: {{ $product->stock }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Quantity</label>
                <input type="number" name="items[${itemCount}][quantity]" class="form-control" min="1" required>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-danger w-100" onclick="removeItem(${itemCount})">Remove</button>
            </div>
        </div>
    `;
    container.appendChild(itemRow);
    itemCount++;
}

function removeItem(index) {
    const itemRows = document.querySelectorAll('.item-row');
    if (itemRows.length > 1) {
        itemRows[index].remove();
    } else {
        alert('You must have at least one item!');
    }
}
</script>
@endsection