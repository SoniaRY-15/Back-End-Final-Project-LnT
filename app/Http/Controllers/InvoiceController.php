<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Generate invoice number
        $invoiceNumber = 'INV-' . date('YmdHis') . '-' . Auth::id();

        // Calculate total
        $totalPrice = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            
            // Check stock
            if ($product->stock < $item['quantity']) {
                return back()->withErrors(['stock' => 'Stock tidak cukup untuk ' . $product->name]);
            }

            $totalPrice += $product->price * $item['quantity'];
        }

        // Create invoice
        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'user_id' => Auth::id(),
            'address' => $validated['address'],
            'postal_code' => $validated['postal_code'],
            'total_price' => $totalPrice,
        ]);

        // Create invoice items
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $subtotal = $product->price * $item['quantity'];

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
            ]);

            // Reduce stock
            $product->decrement('stock', $item['quantity']);
        }

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully.');
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
            return redirect()->route('products.index')->with('error', 'Unauthorized access.');
        }

        $invoice->load('invoiceItems.product', 'user');
        return view('user.invoice.print', compact('invoice'));
    }

    // USER - List invoices
    public function index()
    {
        $invoices = Invoice::where('user_id', Auth::id())->latest()->paginate(10);
        return view('user.invoice.index', compact('invoices'));
    }
}