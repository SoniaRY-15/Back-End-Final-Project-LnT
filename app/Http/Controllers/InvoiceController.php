<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    // USER - Show cart/invoice form
    public function create()
    {
        $products = Product::with('category')->get();
        return view('user.invoice.create', compact('products'));
    }

    // USER - Store invoice
    public function store(Request $request)
    {
    $validated = $request->validate([
        'address' => 'required|string|min:10|max:100',
        'postal_code' => 'required|string|regex:/^\d{5}$/',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    // VALIDATE ALL ITEMS FIRST before any DB operations
    $validated_items = [];
    $totalPrice = 0;

    foreach ($validated['items'] as $item) {
        $product = Product::lockForUpdate()->find($item['product_id']);
        
        if (!$product) {
            return back()->withErrors([
                'stock' => 'Produk tidak ditemukan'
            ])->withInput();
        }

        if ($product->stock < $item['quantity']) {
            return back()->withErrors([
                'stock' => "Stock tidak cukup untuk {$product->name}. Available: {$product->stock}"
            ])->withInput();
        }

        $subtotal = $product->price * $item['quantity'];
        $totalPrice += $subtotal;
        
        $validated_items[] = [
            'product' => $product,
            'quantity' => $item['quantity'],
            'subtotal' => $subtotal,
        ];
    }

    // Use transaction for atomicity
    return DB::transaction(function () use ($validated_items, $validated, $totalPrice) {
        $invoiceNumber = 'INV-' . date('YmdHis') . '-' . Auth::id();

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'user_id' => Auth::id(),
            'address' => $validated['address'],
            'postal_code' => $validated['postal_code'],
            'total_price' => $totalPrice,
        ]);

        foreach ($validated_items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);

            // Decrement after confirmed
            $item['product']->decrement('stock', $item['quantity']);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    });
}

    // USER - Show invoice
    public function show(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'Unauthorized access.');
        }

        $invoice->load('invoiceItems.product');
        return view('user.invoice.show', compact('invoice'));
    }

    // USER - Print invoice
    public function print(Invoice $invoice)
    {
    if ($invoice->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
        return redirect()->route('products.index')
            ->with('error', 'Unauthorized access.');
    }

    // Correct syntax for multiple relationships
    $invoice->load(['invoiceItems.product', 'user']);
    return view('user.invoice.print', compact('invoice'));
    }

    // USER - List invoices
    public function index()
    {
        $invoices = Invoice::where('user_id', Auth::id())->latest()->paginate(10);
        return view('user.invoice.index', compact('invoices'));
    }
}