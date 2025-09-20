@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>Stock Movements History
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('stock-movements.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label for="type" class="form-label">Movement Type</label>
                                <select class="form-select" name="type" id="type">
                                    <option value="">All Types</option>
                                    <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Stock In</option>
                                    <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Stock Out</option>
                                    <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="product_id" class="form-label">Product</label>
                                <select class="form-select" name="product_id" id="product_id">
                                    <option value="">All Products</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="warehouse_id" class="form-label">Warehouse</label>
                                <select class="form-select" name="warehouse_id" id="warehouse_id">
                                    <option value="">All Warehouses</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" name="date_from" id="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" name="date_to" id="date_to" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" name="search" id="search" 
                                       value="{{ request('search') }}" placeholder="Reference, notes...">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                                <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Clear Filters
                                </a>
                            </div>
                        </div>
                    </form>

                    @if($movements->count() > 0)
                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <div class="h4 mb-1">{{ $movements->where('type', 'in')->count() }}</div>
                                        <small>Stock In</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <div class="h4 mb-1">{{ $movements->where('type', 'out')->count() }}</div>
                                        <small>Stock Out</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <div class="h4 mb-1">{{ $movements->where('type', 'adjustment')->count() }}</div>
                                        <small>Adjustments</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <div class="h4 mb-1">{{ $movements->count() }}</div>
                                        <small>Total Movements</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Product</th>
                                        <th>Warehouse</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Before/After</th>
                                        <th>Reference</th>
                                        <th>Reason</th>
                                        <th>User</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($movements as $movement)
                                    <tr>
                                        <td>
                                            <div class="small">
                                                <strong>{{ $movement->movement_date->format('M d, Y') }}</strong><br>
                                                <span class="text-muted">{{ $movement->movement_date->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($movement->product)
                                                <div>
                                                    <strong>{{ $movement->product->name }}</strong><br>
                                                    <small class="text-muted">{{ $movement->product->sku }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">Product deleted</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($movement->warehouse)
                                                <span class="badge bg-secondary">{{ $movement->warehouse->name }}</span>
                                            @else
                                                <span class="text-muted">Warehouse deleted</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $typeColors = [
                                                    'in' => 'success',
                                                    'out' => 'danger',
                                                    'adjustment' => 'warning'
                                                ];
                                                $typeIcons = [
                                                    'in' => 'arrow-up',
                                                    'out' => 'arrow-down',
                                                    'adjustment' => 'edit'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $typeColors[$movement->type] ?? 'secondary' }}">
                                                <i class="fas fa-{{ $typeIcons[$movement->type] ?? 'question' }} me-1"></i>
                                                {{ ucfirst($movement->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ $movement->quantity >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $movement->quantity >= 0 ? '+' : '' }}{{ number_format($movement->quantity) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <span class="text-muted">{{ number_format($movement->quantity_before) }}</span>
                                                <i class="fas fa-arrow-right mx-1"></i>
                                                <span class="fw-bold">{{ number_format($movement->quantity_after) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($movement->reference_number)
                                                <span class="badge bg-info">{{ $movement->reference_number }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($movement->reason)
                                                <span class="small">{{ Str::limit($movement->reason, 30) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($movement->user)
                                                <small class="text-muted">{{ $movement->user->name ?? 'System' }}</small>
                                            @else
                                                <small class="text-muted">System</small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('stock-movements.show', $movement) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $movements->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No stock movements found</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['type', 'product_id', 'warehouse_id', 'date_from', 'date_to', 'search']))
                                    Try adjusting your filters to see more results.
                                @else
                                    Stock movements will appear here when inventory changes are made.
                                @endif
                            </p>
                            @if(request()->hasAny(['type', 'product_id', 'warehouse_id', 'date_from', 'date_to', 'search']))
                                <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-times me-2"></i>Clear Filters
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
