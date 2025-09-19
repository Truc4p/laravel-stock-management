@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-truck me-2"></i>{{ $supplier->name }}
                        <span class="badge ms-2 {{ $supplier->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </h4>
                    <div>
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Suppliers
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Contact Information -->
                        <div class="col-md-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-address-book me-2"></i>Contact Information
                            </h6>
                            @if($supplier->contact_person)
                                <div class="mb-2">
                                    <strong>Contact Person:</strong>
                                    <span class="ms-2">{{ $supplier->contact_person }}</span>
                                </div>
                            @endif
                            @if($supplier->email)
                                <div class="mb-2">
                                    <strong>Email:</strong>
                                    <span class="ms-2">
                                        <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">
                                            {{ $supplier->email }}
                                        </a>
                                    </span>
                                </div>
                            @endif
                            @if($supplier->phone)
                                <div class="mb-2">
                                    <strong>Phone:</strong>
                                    <span class="ms-2">
                                        <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">
                                            {{ $supplier->phone }}
                                        </a>
                                    </span>
                                </div>
                            @endif
                            @if(!$supplier->contact_person && !$supplier->email && !$supplier->phone)
                                <p class="text-muted">No contact information available</p>
                            @endif
                        </div>

                        <!-- Address Information -->
                        <div class="col-md-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Address
                            </h6>
                            @if($supplier->address || $supplier->city || $supplier->country)
                                <address class="mb-0">
                                    @if($supplier->address)
                                        {{ $supplier->address }}<br>
                                    @endif
                                    @if($supplier->city || $supplier->postal_code)
                                        {{ $supplier->city }}@if($supplier->city && $supplier->postal_code), @endif{{ $supplier->postal_code }}<br>
                                    @endif
                                    @if($supplier->country)
                                        {{ $supplier->country }}
                                    @endif
                                </address>
                            @else
                                <p class="text-muted">No address information available</p>
                            @endif
                        </div>

                        <!-- Statistics -->
                        <div class="col-md-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Statistics
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <div class="h3 mb-1">{{ $supplier->products->count() }}</div>
                                            <small>Products</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <div class="h3 mb-1">
                                                ${{ number_format($supplier->products->sum(function($product) {
                                                    return $product->inventory->sum(function($inventory) use ($product) {
                                                        return $inventory->current_stock * $product->price;
                                                    });
                                                }), 2) }}
                                            </div>
                                            <small>Total Value</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-muted">
                                    <strong>Added:</strong> {{ $supplier->created_at->format('M d, Y') }}<br>
                                    <strong>Updated:</strong> {{ $supplier->updated_at->format('M d, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    @if($supplier->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-sticky-note me-2"></i>Notes
                                </h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $supplier->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Products from Supplier -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-box me-2"></i>Products from Supplier
                    </h5>
                    <div>
                        <a href="{{ route('products.create') }}?supplier={{ $supplier->id }}" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-plus me-2"></i>Add Product from Supplier
                        </a>
                        <a href="{{ route('products.index') }}?supplier_id={{ $supplier->id }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-external-link-alt me-2"></i>View All Products
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($supplier->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Total Stock</th>
                                        <th>Stock Value</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supplier->products as $product)
                                    <tr>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            @if($product->description)
                                                <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $product->sku }}</span>
                                        </td>
                                        <td>
                                            @if($product->category)
                                                <span class="badge bg-info">{{ $product->category->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>${{ number_format($product->price, 2) }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $totalStock = $product->inventory->sum('current_stock');
                                                $minStock = $product->minimum_stock ?? 0;
                                            @endphp
                                            <span class="badge {{ $totalStock <= $minStock ? 'bg-danger' : 'bg-success' }}">
                                                {{ number_format($totalStock) }}
                                            </span>
                                            @if($totalStock <= $minStock)
                                                <br><small class="text-danger">Low Stock!</small>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>${{ number_format($totalStock * $product->price, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('products.show', $product) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View Product">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('products.edit', $product) }}" 
                                                   class="btn btn-sm btn-outline-secondary" title="Edit Product">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <th colspan="5">Total Supplier Value:</th>
                                        <th>
                                            ${{ number_format($supplier->products->sum(function($product) {
                                                return $product->inventory->sum(function($inventory) use ($product) {
                                                    return $inventory->current_stock * $product->price;
                                                });
                                            }), 2) }}
                                        </th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No products from this supplier</h6>
                            <p class="text-muted">Start by adding products supplied by this vendor.</p>
                            <a href="{{ route('products.create') }}?supplier={{ $supplier->id }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add First Product
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
