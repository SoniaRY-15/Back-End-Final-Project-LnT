@extends('layouts.app')

@section('title', 'Products - Admin')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📦 Product Management</h5>
                <a href="{{ route('admin.products.create') }}" class="btn btn-light btn-sm">+ Create Product</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>
                                    @if($product->stock == 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                    @else
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No products found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection