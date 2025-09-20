@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-box me-2"></i>Product Details
    </h1>
    <div class="btn-group" role="group">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Products
        </a>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Edit Product
        </a>
    </div>
</div>

<div class="row">
    <!-- Product Information -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Product Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Product Name</label>
                            <p class="fw-bold fs-5">{{ $product->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">SKU</label>
                            <p><code class="fs-6">{{ $product->sku }}</code></p>
                        </div>
                        
                        @if($product->barcode)
                        <div class="mb-3">
                            <label class="form-label text-muted">Barcode</label>
                            <p><code class="fs-6">{{ $product->barcode }}</code></p>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Category</label>
                            <p><span class="badge bg-info fs-6">{{ $product->category->name }}</span></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Supplier</label>
                            <p class="fw-semibold">{{ $product->supplier->name }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Cost Price</label>
                            <p class="fw-bold text-success fs-5">${{ number_format($product->cost_price, 2) }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Selling Price</label>
                            <p class="fw-bold text-primary fs-5">${{ number_format($product->selling_price, 2) }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Profit Margin</label>
                            @php
                                $margin = $product->cost_price > 0 ? (($product->selling_price - $product->cost_price) / $product->cost_price) * 100 : 0;
                            @endphp
                            <p class="fw-bold {{ $margin > 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($margin, 1) }}%
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Unit</label>
                            <p>{{ $product->unit }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <p>
                                <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }} fs-6">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($product->description)
                <div class="mb-3">
                    <label class="form-label text-muted">Description</label>
                    <p>{{ $product->description }}</p>
                </div>
                @endif
                
                @if($product->location)
                <div class="mb-3">
                    <label class="form-label text-muted">Storage Location</label>
                    <p><i class="fas fa-map-marker-alt me-2"></i>{{ $product->location }}</p>
                </div>
                @endif
                
                @if($product->notes)
                <div class="mb-3">
                    <label class="form-label text-muted">Notes</label>
                    <p class="text-muted">{{ $product->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Stock Information -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Stock Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Minimum Stock</label>
                    <p class="fw-bold">
                        <span class="badge bg-warning">{{ $product->minimum_stock }} {{ $product->unit }}</span>
                    </p>
                </div>
                
                @if($product->maximum_stock)
                <div class="mb-3">
                    <label class="form-label text-muted">Maximum Stock</label>
                    <p class="fw-bold">
                        <span class="badge bg-info">{{ $product->maximum_stock }} {{ $product->unit }}</span>
                    </p>
                </div>
                @endif
                
                @php
                    $totalStock = $product->inventory->sum('quantity');
                @endphp
                <div class="mb-3">
                    <label class="form-label text-muted">Current Stock</label>
                    <p class="fw-bold fs-4">
                        @if($totalStock < $product->minimum_stock)
                            <span class="text-danger">{{ $totalStock }} {{ $product->unit }}</span>
                            <br><small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Below minimum stock!</small>
                        @elseif($product->maximum_stock && $totalStock > $product->maximum_stock)
                            <span class="text-warning">{{ $totalStock }} {{ $product->unit }}</span>
                            <br><small class="text-warning"><i class="fas fa-exclamation-circle me-1"></i>Above maximum stock!</small>
                        @else
                            <span class="text-success">{{ $totalStock }} {{ $product->unit }}</span>
                        @endif
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Stock Value (Cost)</label>
                    <p class="fw-bold text-success">${{ number_format($totalStock * $product->cost_price, 2) }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Stock Value (Selling)</label>
                    <p class="fw-bold text-primary">${{ number_format($totalStock * $product->selling_price, 2) }}</p>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Product
                    </a>
                    <a href="{{ route('inventory.index', ['search' => $product->sku]) }}" class="btn btn-info">
                        <i class="fas fa-boxes me-2"></i>View Inventory
                    </a>
                    <a href="{{ route('stock-movements.index', ['search' => $product->sku]) }}" class="btn btn-secondary">
                        <i class="fas fa-exchange-alt me-2"></i>Stock Movements
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inventory by Warehouse -->
@if($product->inventory->count() > 0)
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-warehouse me-2"></i>Inventory by Warehouse
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Warehouse</th>
                        <th>Quantity</th>
                        <th>Reserved</th>
                        <th>Available</th>
                        <th>Value (Cost)</th>
                        <th>Value (Selling)</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product->inventory as $inventory)
                    <tr>
                        <td>
                            <strong>{{ $inventory->warehouse->name }}</strong>
                            <br><small class="text-muted">{{ $inventory->warehouse->location }}</small>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $inventory->quantity }} {{ $product->unit }}</span>
                        </td>
                        <td>
                            <span class="text-warning">{{ $inventory->reserved_quantity ?? 0 }} {{ $product->unit }}</span>
                        </td>
                        <td>
                            @php
                                $available = $inventory->quantity - ($inventory->reserved_quantity ?? 0);
                            @endphp
                            <span class="fw-bold {{ $available > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $available }} {{ $product->unit }}
                            </span>
                        </td>
                        <td class="text-success">
                            ${{ number_format($inventory->quantity * $product->cost_price, 2) }}
                        </td>
                        <td class="text-primary">
                            ${{ number_format($inventory->quantity * $product->selling_price, 2) }}
                        </td>
                        <td class="text-muted">
                            {{ $inventory->updated_at->format('M j, Y') }}
                            <br><small>{{ $inventory->updated_at->format('g:i A') }}</small>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Product Timeline -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-clock me-2"></i>Product Timeline
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-1"><strong>Created:</strong></p>
                <p class="text-muted">{{ $product->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>Last Modified:</strong></p>
                <p class="text-muted">{{ $product->updated_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
