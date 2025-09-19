<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Inventory;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_suppliers' => Supplier::count(),
            'total_warehouses' => Warehouse::count(),
            'low_stock_products' => Product::lowStock()->count(),
            'total_stock_value' => Inventory::join('products', 'inventory.product_id', '=', 'products.id')
                ->sum(\DB::raw('inventory.quantity * products.cost_price')),
            'recent_movements' => StockMovement::with(['product', 'warehouse'])
                ->orderBy('movement_date', 'desc')
                ->limit(10)
                ->get(),
            'low_stock_items' => Inventory::with(['product', 'warehouse'])
                ->lowStock()
                ->limit(10)
                ->get()
        ];

        return view('dashboard', compact('stats'));
    }
}
