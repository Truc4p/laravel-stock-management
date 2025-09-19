<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::with(['product.category', 'warehouse']);

        if ($request->has('warehouse_id')) {
            $query->where('warehouse_id', $request->get('warehouse_id'));
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->get('low_stock')) {
            $query->lowStock();
        }

        $inventory = $query->paginate(15);
        $warehouses = Warehouse::active()->get();

        return view('inventory.index', compact('inventory', 'warehouses'));
    }

    /**
     * Show stock movement form
     */
    public function move()
    {
        $products = Product::active()->get();
        $warehouses = Warehouse::active()->get();
        return view('inventory.move', compact('products', 'warehouses'));
    }

    /**
     * Process stock movement
     */
    public function processMovement(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:in,out,adjustment,transfer',
            'quantity' => 'required|integer',
            'unit_cost' => 'nullable|numeric|min:0',
            'reference_number' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $inventory = Inventory::firstOrCreate(
                [
                    'product_id' => $request->product_id,
                    'warehouse_id' => $request->warehouse_id
                ],
                [
                    'quantity' => 0,
                    'reserved_quantity' => 0,
                    'average_cost' => 0
                ]
            );

            $quantityBefore = $inventory->quantity;
            $movementQuantity = $request->quantity;

            // Adjust quantity based on movement type
            if ($request->type === 'out') {
                $movementQuantity = -abs($movementQuantity);
            } elseif ($request->type === 'adjustment') {
                // For adjustments, the quantity can be positive or negative
                // The user should input the adjustment amount (e.g., +10 or -5)
            }

            $quantityAfter = $quantityBefore + $movementQuantity;

            // Prevent negative stock for outbound movements
            if ($quantityAfter < 0 && in_array($request->type, ['out'])) {
                throw new \Exception('Insufficient stock. Available: ' . $quantityBefore);
            }

            // Update inventory
            $inventory->update([
                'quantity' => $quantityAfter,
                'last_movement_at' => now()
            ]);

            // Update average cost for inbound movements
            if ($movementQuantity > 0 && $request->unit_cost) {
                $totalCost = ($inventory->average_cost * $quantityBefore) + ($request->unit_cost * abs($movementQuantity));
                $totalQuantity = $quantityAfter;
                $inventory->update([
                    'average_cost' => $totalQuantity > 0 ? $totalCost / $totalQuantity : 0
                ]);
            }

            // Create stock movement record
            StockMovement::create([
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'user_id' => auth()->id(),
                'type' => $request->type,
                'quantity' => $movementQuantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'unit_cost' => $request->unit_cost,
                'reference_number' => $request->reference_number,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'movement_date' => now()
            ]);
        });

        return redirect()->route('inventory.index')
            ->with('success', 'Stock movement processed successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        $inventory->load(['product.category', 'warehouse']);
        $movements = StockMovement::where('product_id', $inventory->product_id)
            ->where('warehouse_id', $inventory->warehouse_id)
            ->with('user')
            ->orderBy('movement_date', 'desc')
            ->paginate(10);

        return view('inventory.show', compact('inventory', 'movements'));
    }

    /**
     * Adjust stock levels
     */
    public function adjust(Request $request, Inventory $inventory)
    {
        $request->validate([
            'adjustment_type' => 'required|in:set,add,subtract',
            'quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $inventory) {
            $quantityBefore = $inventory->quantity;
            $newQuantity = $quantityBefore;

            switch ($request->adjustment_type) {
                case 'set':
                    $newQuantity = $request->quantity;
                    break;
                case 'add':
                    $newQuantity = $quantityBefore + $request->quantity;
                    break;
                case 'subtract':
                    $newQuantity = $quantityBefore - $request->quantity;
                    break;
            }

            $movementQuantity = $newQuantity - $quantityBefore;

            $inventory->update([
                'quantity' => $newQuantity,
                'last_movement_at' => now()
            ]);

            StockMovement::create([
                'product_id' => $inventory->product_id,
                'warehouse_id' => $inventory->warehouse_id,
                'user_id' => auth()->id(),
                'type' => 'adjustment',
                'quantity' => $movementQuantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $newQuantity,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'movement_date' => now()
            ]);
        });

        return redirect()->back()
            ->with('success', 'Stock adjusted successfully.');
    }
}
