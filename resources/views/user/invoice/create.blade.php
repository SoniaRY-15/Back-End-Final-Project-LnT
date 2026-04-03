@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">🛒 Create Invoice</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.store') }}" method="POST">
                    @csrf

                    <h6 class="mb-3">Select Products</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="cartTable">
                                @forelse($products as $product)
                                @if($product->stock > 0)
                                <tr data-product-id="{{ $product->id }}" data-price="{{ $product->price }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input product-checkbox"
                                            value="{{ $product->id }}" onchange="updateTotal()">
                                        <label class="ms-2">{{ $product->name }}</label>
                                    </td>
                                    <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm quantity-input"
                                            name="items[{{ $product->id }}][quantity]" value="1" min="1"
                                            max="{{ $product->stock }}" style="width: 80px;" onchange="updateTotal()"
                                            disabled>
                                    </td>
                                    <td class="subtotal">Rp. 0</td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No products available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address"
                                name="address" rows="3" placeholder="Enter delivery address"
                                required>{{ old('address') }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="postal_code" class="form-label">Postal Code (5 digits)</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                id="postal_code" name="postal_code" placeholder="e.g., 12345" maxlength="5" required
                                value="{{ old('postal_code') }}">
                            @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <strong>Total Price:</strong> Rp. <span id="totalPrice">0</span>
                    </div>

                    <input type="hidden" id="itemsInput" name="items" value="[]">

                    <button type="submit" class="btn btn-success btn-lg w-100">Create Invoice</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-header">
                <h6 class="mb-0">Cart Summary</h6>
            </div>
            <div class="card-body">
                <p class="text-muted">Select products and quantities above, then fill in delivery details.</p>
                <div id="cartSummary" class="small">No items selected</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateTotal() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    let totalPrice = 0;
    const items = [];
    const summary = [];

    checkboxes.forEach((checkbox, index) => {
        const row = checkbox.closest('tr');
        const quantityInput = row.querySelector('.quantity-input');
        const price = parseInt(row.dataset.price);
        const quantity = parseInt(quantityInput.value) || 0;
        const subtotal = price * quantity;

        totalPrice += subtotal;
        row.querySelector('.subtotal').textContent = 'Rp. ' + subtotal.toLocaleString('id-ID');

        // Build items array for form submission
        items.push({
            product_id: checkbox.value,
            quantity: quantity
        });

        const productName = row.querySelector('label').textContent.trim();
        summary.push(`<div class="mb-2">${productName} x${quantity} = Rp. ${subtotal.toLocaleString('id-ID')}</div>`);
    });

    document.getElementById('totalPrice').textContent = totalPrice.toLocaleString('id-ID');
    
    // Convert items array to form-friendly format
    const form = document.querySelector('form');
    form.innerHTML = form.innerHTML; // Reset
    
    items.forEach((item, index) => {
        const input1 = document.createElement('input');
        input1.type = 'hidden';
        input1.name = `items[${index}][product_id]`;
        input1.value = item.product_id;
        form.appendChild(input1);

        const input2 = document.createElement('input');
        input2.type = 'hidden';
        input2.name = `items[${index}][quantity]`;
        input2.value = item.quantity;
        form.appendChild(input2);
    });

    document.getElementById('cartSummary').innerHTML = summary.length > 0 ? 
        summary.join('') : 'No items selected';
}

document.querySelectorAll('.product-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const row = this.closest('tr');
        const quantityInput = row.querySelector('.quantity-input');
        quantityInput.disabled = !this.checked;
        if (!this.checked) quantityInput.value = 1;
        updateTotal();
    });
});
</script>
@endsection