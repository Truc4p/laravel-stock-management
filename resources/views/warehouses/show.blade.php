@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-warehouse me-2"></i>{{ $warehouse->name }}
                        <span class="badge ms-2 {{ $warehouse->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </h4>
                    <div>
                        <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('warehouses.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Warehouses
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h6>
                            <div class="mb-3">
                                <strong>Warehouse Code:</strong>
                                <span class="badge bg-primary ms-2">{{ $warehouse->code }}</span>
                            </div>
                            @if($warehouse->description)
                                <div class="mb-3">
                                    <strong>Description:</strong>
                                    <p class="text-muted mb-0">{{ $warehouse->description }}</p>
                                </div>
                            @endif
                            <div class="mb-3">
                                <strong>Total Inventory Items:</strong>
                                <span class="badge bg-info ms-2">{{ $warehouse->inventory()->count() }}</span>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="col-md-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Location
                            </h6>
                            <div class="mb-3">
                                <strong>Address:</strong>
                                <address class="mb-0">
                                    {{ $warehouse->address }}<br>
                                    {{ $warehouse->city }}, {{ $warehouse->postal_code }}<br>
                                    {{ $warehouse->country }}
                                </address>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-address-book me-2"></i>Contact Information
                            </h6>
                            @if($warehouse->manager_name)
                                <div class="mb-2">
                                    <strong>Manager:</strong>
                                    <span class="ms-2">{{ $warehouse->manager_name }}</span>
                                </div>
                            @endif
                            @if($warehouse->contact_phone)
                                <div class="mb-2">
                                    <strong>Phone:</strong>
                                    <span class="ms-2">{{ $warehouse->contact_phone }}</span>
                                </div>
                            @endif
                            @if($warehouse->contact_email)
                                <div class="mb-2">
                                    <strong>Email:</strong>
                                    <span class="ms-2">{{ $warehouse->contact_email }}</span>
                                </div>
                            @endif
                            @if(!$warehouse->manager_name && !$warehouse->contact_phone && !$warehouse->contact_email)
                                <p class="text-muted">No contact information available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>Current Inventory
                    </h5>
                    <a href="{{ route('inventory.index') }}?warehouse={{ $warehouse->id }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-external-link-alt me-2"></i>View All Inventory
                    </a>
                </div>
                <div class="card-body">
                    @if($warehouse->inventory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Current Stock</th>
                                        <th>Unit Price</th>
                                        <th>Total Value</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($warehouse->inventory as $inventory)
                                    <tr>
                                        <td>
                                            <strong>{{ $inventory->product->name }}</strong>
                                            @if($inventory->product->description)
                                                <br><small class="text-muted">{{ Str::limit($inventory->product->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $inventory->product->sku }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $inventory->current_stock <= $inventory->min_stock_level ? 'bg-danger' : 'bg-success' }}">
                                                {{ number_format($inventory->current_stock) }}
                                            </span>
                                            @if($inventory->current_stock <= $inventory->min_stock_level)
                                                <br><small class="text-danger">Low Stock!</small>
                                            @endif
                                        </td>
                                        <td>${{ number_format($inventory->product->price, 2) }}</td>
                                        <td>
                                            <strong>${{ number_format($inventory->current_stock * $inventory->product->price, 2) }}</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $inventory->updated_at->format('M d, Y') }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <th colspan="4">Total Inventory Value:</th>
                                        <th>
                                            ${{ number_format($warehouse->inventory->sum(function($inventory) {
                                                return $inventory->current_stock * $inventory->product->price;
                                            }), 2) }}
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No inventory found</h6>
                            <p class="text-muted">This warehouse doesn't have any products in stock yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Stock Movements -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>Recent Stock Movements
                    </h5>
                    <a href="{{ route('stock-movements.index') }}?warehouse={{ $warehouse->id }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-external-link-alt me-2"></i>View All Movements
                    </a>
                </div>
                <div class="card-body">
                    @if($warehouse->stockMovements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Reference</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($warehouse->stockMovements as $movement)
                                    <tr>
                                        <td>
                                            <small>{{ $movement->created_at->format('M d, Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $movement->product->name }}</strong>
                                            <br><small class="text-muted">{{ $movement->product->sku }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $movement->type === 'in' ? 'bg-success' : 'bg-danger' }}">
                                                <i class="fas fa-arrow-{{ $movement->type === 'in' ? 'up' : 'down' }} me-1"></i>
                                                {{ ucfirst($movement->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ $movement->type === 'in' ? 'text-success' : 'text-danger' }}">
                                                {{ $movement->type === 'in' ? '+' : '-' }}{{ number_format($movement->quantity) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($movement->reference_number)
                                                <span class="badge bg-info">{{ $movement->reference_number }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($movement->notes)
                                                <small class="text-muted">{{ Str::limit($movement->notes, 50) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No stock movements found</h6>
                            <p class="text-muted">No recent stock movements recorded for this warehouse.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
