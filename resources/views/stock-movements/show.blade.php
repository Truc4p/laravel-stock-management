@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>Stock Movement Details
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
                        <span class="badge ms-2 bg-{{ $typeColors[$stockMovement->type] ?? 'secondary' }}">
                            <i class="fas fa-{{ $typeIcons[$stockMovement->type] ?? 'question' }} me-1"></i>
                            {{ ucfirst($stockMovement->type) }}
                        </span>
                    </h4>
                    <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Stock Movements
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Movement Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Movement Information
                            </h6>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Date & Time:</strong></div>
                                <div class="col-sm-8">{{ $stockMovement->movement_date->format('M d, Y \a\t H:i') }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Type:</strong></div>
                                <div class="col-sm-8">
                                    <span class="badge bg-{{ $typeColors[$stockMovement->type] ?? 'secondary' }}">
                                        <i class="fas fa-{{ $typeIcons[$stockMovement->type] ?? 'question' }} me-1"></i>
                                        {{ ucfirst($stockMovement->type) }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Reference:</strong></div>
                                <div class="col-sm-8">
                                    @if($stockMovement->reference_number)
                                        <span class="badge bg-info">{{ $stockMovement->reference_number }}</span>
                                    @else
                                        <span class="text-muted">No reference</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>User:</strong></div>
                                <div class="col-sm-8">
                                    @if($stockMovement->user)
                                        {{ $stockMovement->user->name }}
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </div>
                            </div>
                            @if($stockMovement->unit_cost)
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Unit Cost:</strong></div>
                                    <div class="col-sm-8">${{ number_format($stockMovement->unit_cost, 2) }}</div>
                                </div>
                            @endif
                        </div>

                        <!-- Quantity Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-calculator me-2"></i>Quantity Changes
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="h5 text-muted mb-1">{{ number_format($stockMovement->quantity_before) }}</div>
                                            <small class="text-muted">Before</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="h4 {{ $stockMovement->quantity >= 0 ? 'text-success' : 'text-danger' }} mb-1">
                                                {{ $stockMovement->quantity >= 0 ? '+' : '' }}{{ number_format($stockMovement->quantity) }}
                                            </div>
                                            <small class="text-muted">Change</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="h5 text-primary mb-1">{{ number_format($stockMovement->quantity_after) }}</div>
                                            <small class="text-muted">After</small>
                                        </div>
                                    </div>
                                    @if($stockMovement->unit_cost)
                                        <hr class="my-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="h6 text-success mb-1">
                                                    ${{ number_format(abs($stockMovement->quantity) * $stockMovement->unit_cost, 2) }}
                                                </div>
                                                <small class="text-muted">Total Value</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-box me-2"></i>Product Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($stockMovement->product)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Product Name:</strong></div>
                                    <div class="col-sm-8">{{ $stockMovement->product->name }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>SKU:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-secondary">{{ $stockMovement->product->sku }}</span>
                                    </div>
                                </div>
                                @if($stockMovement->product->category)
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Category:</strong></div>
                                        <div class="col-sm-8">
                                            <span class="badge bg-info">{{ $stockMovement->product->category->name }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if($stockMovement->product->supplier)
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Supplier:</strong></div>
                                        <div class="col-sm-8">{{ $stockMovement->product->supplier->name }}</div>
                                    </div>
                                @endif
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Current Price:</strong></div>
                                    <div class="col-sm-8">${{ number_format($stockMovement->product->price, 2) }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Actions:</strong></div>
                                    <div class="col-sm-8">
                                        <a href="{{ route('products.show', $stockMovement->product) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View Product
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($stockMovement->product->description)
                            <div class="row">
                                <div class="col-12">
                                    <strong>Description:</strong>
                                    <p class="text-muted mt-1">{{ $stockMovement->product->description }}</p>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                            <h6 class="text-muted">Product information not available</h6>
                            <p class="text-muted">The product associated with this movement may have been deleted.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Warehouse Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-warehouse me-2"></i>Warehouse Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($stockMovement->warehouse)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Warehouse:</strong></div>
                                    <div class="col-sm-8">{{ $stockMovement->warehouse->name }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Code:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-primary">{{ $stockMovement->warehouse->code }}</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Location:</strong></div>
                                    <div class="col-sm-8">{{ $stockMovement->warehouse->city }}, {{ $stockMovement->warehouse->country }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Status:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge {{ $stockMovement->warehouse->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $stockMovement->warehouse->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Actions:</strong></div>
                                    <div class="col-sm-8">
                                        <a href="{{ route('warehouses.show', $stockMovement->warehouse) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View Warehouse
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                            <h6 class="text-muted">Warehouse information not available</h6>
                            <p class="text-muted">The warehouse associated with this movement may have been deleted.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Additional Details -->
            @if($stockMovement->reason || $stockMovement->notes)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-sticky-note me-2"></i>Additional Details
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($stockMovement->reason)
                            <div class="mb-3">
                                <h6 class="text-primary">Reason:</h6>
                                <p class="mb-0">{{ $stockMovement->reason }}</p>
                            </div>
                        @endif
                        @if($stockMovement->notes)
                            <div class="mb-0">
                                <h6 class="text-primary">Notes:</h6>
                                <p class="mb-0">{{ $stockMovement->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
