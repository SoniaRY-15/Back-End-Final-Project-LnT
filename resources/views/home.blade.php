@extends('layouts.app')

@section('title', 'Home - Inventory System')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="col-md-8 text-center">
        <h1 class="display-4 mb-4">ChipiChapa Inventory System</h1>
        <p class="lead text-muted mb-5">Tugas Final Project LnT Backend oleh Sonia RY</p>
        
        @auth
            @if(Auth::user()->role === 'admin')
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark btn-lg">Kelola Produk</a>
                    <a href="{{ route('logout') }}" class="btn btn-outline-dark btn-lg" onclick="document.getElementById('logout-form').submit(); return false;">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </div>
            @else
                <div class="btn-group" role="group">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark btn-lg">Lihat Katalog</a>
                    <a href="{{ route('invoices.index') }}" class="btn btn-outline-dark btn-lg">Invoice</a>
                    <a href="{{ route('logout') }}" class="btn btn-outline-dark btn-lg" onclick="document.getElementById('logout-form').submit(); return false;">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </div>
            @endif
        @else
            <div class="btn-group" role="group">
                <a href="{{ route('login') }}" class="btn btn-outline-dark btn-lg px-5 py-3">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-dark btn-lg px-5 py-3">Sign Up</a>
            </div>
        @endauth

        <div class="mt-3 pt-5">
            <p class="text-muted small">
                <strong>Demo Admin Account:</strong><br>
                Email: admin@gmail.com<br>
                Password: admin123
            </p>
        </div>
    </div>
</div>
@endsection