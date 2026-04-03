@extends('layouts.app')

@section('title', 'Product Catalog')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">📚 Product Catalog</h2>
    </div>
</div>

<div class="row g-4">
    @forelse($products as $product)
    <div class="col-md-3">
        <div class="card h-100">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
            @else
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                <span class="text-muted">No Image</span>
            </div>
            @endif
            <div class="card-body">
                <h6 class="card-title">{{ $product->name }}</h6>
                <p class="card-text text-muted small">{{ $product->category->name }}</p>
                <h5 class="text-primary mb-2">Rp. {{ number_format($product->price, 0, ',', '.') }}</h5>
                @if($product->stock == 0)
                <p class="text-danger small">Barang sudah habis, silakan tunggu hingga barang di-restock ulang</p>
                <button class="btn btn-sm btn-secondary w-100" disabled>Out of Stock</button>
                @else
                <small class="text-success">Stock: {{ $product->stock }}</small><br>
                <button class="btn btn-sm btn-primary w-100 mt-2"
                    onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                    Add to Cart
                </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-md-12">
        <div class="alert alert-info text-center">No products available</div>
    </div>
    @endforelse
</div>

<div class="row mt-4">
    <div class="col-md-12">
        {{ $products->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
function addToCart(productId, productName, price) {
    alert('Added ' + productName + ' to cart. Go to "Create Invoice" to proceed.');
}
</script>
@endsection