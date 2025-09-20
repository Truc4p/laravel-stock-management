@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
    </h1>
    <div class="text-muted">
        <i class="fas fa-calendar me-1"></i>{{ now()->format('l, F j, Y') }}
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ number_format($stats['total_products']) }}</h3>
                    <p class="mb-0 opacity-75">Total Products</p>
                </div>
                <div class="fs-1 opacity-50">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ number_format($stats['total_warehouses']) }}</h3>
                    <p class="mb-0 opacity-75">Warehouses</p>
                </div>
                <div class="fs-1 opacity-50">
                    <i class="fas fa-warehouse"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ number_format($stats['low_stock_products']) }}</h3>
                    <p class="mb-0 opacity-75">Low Stock Items</p>
                </div>
                <div class="fs-1 opacity-50">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">${{ number_format($stats['total_stock_value'], 2) }}</h3>
                    <p class="mb-0 opacity-75">Stock Value</p>
                </div>
                <div class="fs-1 opacity-50">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Stock Movements -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-exchange-alt me-2"></i>Recent Stock Movements
                </h5>
            </div>
            <div class="card-body p-0">
                @if($stats['recent_movements']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Warehouse</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_movements'] as $movement)
                                <tr>
                                    <td>{{ $movement->movement_date->format('M d, Y H:i') }}</td>
                                    <td>
                                        <strong>{{ $movement->product->name }}</strong><br>
                                        <small class="text-muted">{{ $movement->product->sku }}</small>
                                    </td>
                                    <td>{{ $movement->warehouse->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $movement->type === 'in' ? 'success' : ($movement->type === 'out' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($movement->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-{{ $movement->quantity >= 0 ? 'success' : 'danger' }}">
                                            {{ $movement->quantity >= 0 ? '+' : '' }}{{ $movement->quantity }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->reference_number ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-exchange-alt fs-1 text-muted mb-3"></i>
                        <p class="text-muted">No recent stock movements</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Low Stock Alert -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                </h5>
            </div>
            <div class="card-body p-0">
                @if($stats['low_stock_items']->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($stats['low_stock_items'] as $item)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item->product->name }}</strong><br>
                                <small class="text-muted">{{ $item->warehouse->name }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-danger">{{ $item->quantity }}</span><br>
                                <small class="text-muted">Min: {{ $item->product->minimum_stock }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('inventory.index', ['low_stock' => 1]) }}" class="btn btn-sm btn-outline-primary">
                            View All Low Stock Items
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fs-1 text-success mb-3"></i>
                        <p class="text-muted">All items are well stocked</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('products.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i>Add Product
                        </a>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('stock-movements.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-exchange-alt me-2"></i>Stock Movement
                        </a>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('warehouses.create') }}" class="btn btn-info w-100">
                            <i class="fas fa-warehouse me-2"></i>Add Warehouse
                        </a>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('suppliers.create') }}" class="btn btn-warning w-100">
                            <i class="fas fa-truck me-2"></i>Add Supplier
                        </a>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('categories.create') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-tags me-2"></i>Add Category
                        </a>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('inventory.index') }}" class="btn btn-dark w-100">
                            <i class="fas fa-boxes me-2"></i>View Inventory
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
