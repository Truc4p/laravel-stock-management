<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = StockMovement::with(['product', 'warehouse', 'user']);

        // Filter by type
        if ($request->has('type') && in_array($request->type, ['in', 'out', 'adjustment'])) {
            $query->where('type', $request->type);
        }

        // Filter by product
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by warehouse
        if ($request->has('warehouse_id') && $request->warehouse_id) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('movement_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('movement_date', '<=', $request->date_to);
        }

        // Search by reference number or notes
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        $movements = $query->orderBy('movement_date', 'desc')->paginate(15);

        // Get filter options
        $products = Product::orderBy('name')->get(['id', 'name']);
        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        return view('stock-movements.index', compact('movements', 'products', 'warehouses'));
    }

    /**
     * Display the specified resource.
     */
    public function show(StockMovement $stockMovement): View
    {
        $stockMovement->load(['product.category', 'product.supplier', 'warehouse', 'user']);
        
        return view('stock-movements.show', compact('stockMovement'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not implemented - stock movements are created through inventory management
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not implemented - stock movements are created through inventory management
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Not implemented - stock movements are read-only
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Not implemented - stock movements are read-only
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Not implemented - stock movements are read-only
    }
}
