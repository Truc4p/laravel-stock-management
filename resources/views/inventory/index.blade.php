@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-boxes me-2"></i>Inventory
    </h1>
    <a href="{{ route('stock-movements.index') }}" class="btn btn-primary">
        <i class="fas fa-exchange-alt me-2"></i>Stock Movement
    </a>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('inventory.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Products</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by product name or SKU">
                </div>
                <div class="col-md-3">
                    <label for="warehouse_id" class="form-label">Warehouse</label>
                    <select class="form-select" id="warehouse_id" name="warehouse_id">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="low_stock" name="low_stock" 
                               value="1" {{ request('low_stock') ? 'checked' : '' }}>
                        <label class="form-check-label" for="low_stock">
                            Show only low stock items
                        </label>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Inventory Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Inventory Items
            <span class="badge bg-primary ms-2">{{ $inventory->total() }} items</span>
        </h5>
    </div>
    <div class="card-body">
        @if($inventory->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Warehouse</th>
                            <th>Current Stock</th>
                            <th>Reserved</th>
                            <th>Available</th>
                            <th>Min Stock</th>
                            <th>Avg Cost</th>
                            <th>Stock Value</th>
                            <th>Last Movement</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventory as $item)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $item->product->name }}</strong>
                                    <br><small class="text-muted">{{ $item->product->sku }}</small>
                                    <br><span class="badge bg-info">{{ $item->product->category->name }}</span>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $item->warehouse->name }}</strong>
                                <br><small class="text-muted">{{ $item->warehouse->code }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->quantity <= $item->product->minimum_stock ? 'danger' : 'success' }} fs-6">
                                    {{ number_format($item->quantity) }} {{ $item->product->unit }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-warning">
                                    {{ number_format($item->reserved_quantity) }} {{ $item->product->unit }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ number_format($item->available_quantity) }} {{ $item->product->unit }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ number_format($item->product->minimum_stock) }} {{ $item->product->unit }}
                                </small>
                            </td>
                            <td>${{ number_format($item->average_cost, 2) }}</td>
                            <td>
                                <strong>${{ number_format($item->quantity * $item->average_cost, 2) }}</strong>
                            </td>
                            <td>
                                @if($item->last_movement_at)
                                    <small class="text-muted">
                                        {{ $item->last_movement_at->format('M d, Y') }}
                                        <br>{{ $item->last_movement_at->format('H:i') }}
                                    </small>
                                @else
                                    <small class="text-muted">Never</small>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <a href="{{ route('inventory.show', $item) }}" class="btn btn-info btn-sm me-1" title="View History">
                                    <i class="fas fa-history"></i>
                                </a>
                                <button type="button" class="btn btn-warning btn-sm" title="Adjust Stock"
                                        data-bs-toggle="modal" data-bs-target="#adjustModal{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Adjust Stock Modal -->
                                <div class="modal fade" id="adjustModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Adjust Stock - {{ $item->product->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('inventory.adjust', $item) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Current Stock</label>
                                                        <input type="text" class="form-control" 
                                                               value="{{ $item->quantity }} {{ $item->product->unit }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="adjustment_type" class="form-label">Adjustment Type</label>
                                                        <select class="form-select" name="adjustment_type" required>
                                                            <option value="set">Set to specific quantity</option>
                                                            <option value="add">Add quantity</option>
                                                            <option value="subtract">Subtract quantity</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="quantity" class="form-label">Quantity</label>
                                                        <input type="number" class="form-control" name="quantity" required min="0">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="reason" class="form-label">Reason *</label>
                                                        <input type="text" class="form-control" name="reason" required 
                                                               placeholder="e.g., Stock count correction, Damaged items">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="notes" class="form-label">Notes</label>
                                                        <textarea class="form-control" name="notes" rows="2"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Adjust Stock</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($inventory->hasPages())
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $inventory->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-boxes fs-1 text-muted mb-3"></i>
                <h5>No inventory found</h5>
                <p class="text-muted">
                    {{ request()->hasAny(['search', 'warehouse_id', 'low_stock']) ? 'Try adjusting your search criteria.' : 'Start by adding some stock movements to track your inventory.' }}
                </p>
                <a href="{{ route('inventory.move') }}" class="btn btn-primary">
                    <i class="fas fa-exchange-alt me-2"></i>Add Stock Movement
                </a>
            </div>
        @endif
    </div>
</div>

@if(request('low_stock'))
<div class="alert alert-warning mt-4">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Low Stock Alert:</strong> These items are at or below their minimum stock levels and may need restocking.
</div>
@endif
@endsection
