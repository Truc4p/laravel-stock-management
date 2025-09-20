@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-tags me-2"></i>{{ $category->name }}
                        <span class="badge ms-2 {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </h4>
                    <div>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Categories
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Category Information
                            </h6>
                            <div class="mb-3">
                                <strong>Slug:</strong>
                                <span class="badge bg-secondary ms-2">{{ $category->slug }}</span>
                            </div>
                            @if($category->description)
                                <div class="mb-3">
                                    <strong>Description:</strong>
                                    <p class="text-muted mb-0 mt-1">{{ $category->description }}</p>
                                </div>
                            @endif
                            <div class="mb-3">
                                <strong>Created:</strong>
                                <span class="ms-2">{{ $category->created_at->format('M d, Y \a\t H:i') }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Last Updated:</strong>
                                <span class="ms-2">{{ $category->updated_at->format('M d, Y \a\t H:i') }}</span>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Statistics
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <div class="h3 mb-1">{{ $category->products->count() }}</div>
                                            <small>Total Products</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <div class="h3 mb-1">
                                                ${{ number_format($category->products->sum(function($product) {
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products in Category -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-box me-2"></i>Products in Category
                    </h5>
                    <div>
                        <a href="{{ route('products.create') }}?category={{ $category->id }}" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-plus me-2"></i>Add Product to Category
                        </a>
                        <a href="{{ route('products.index') }}?category_id={{ $category->id }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-external-link-alt me-2"></i>View All Products
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($category->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Supplier</th>
                                        <th>Price</th>
                                        <th>Total Stock</th>
                                        <th>Stock Value</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products as $product)
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
                                            @if($product->supplier)
                                                <span class="text-muted">{{ $product->supplier->name }}</span>
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
                                        <th colspan="5">Total Category Value:</th>
                                        <th>
                                            ${{ number_format($category->products->sum(function($product) {
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
                            <h6 class="text-muted">No products in this category</h6>
                            <p class="text-muted">Start by adding products to this category.</p>
                            <a href="{{ route('products.create') }}?category={{ $category->id }}" class="btn btn-primary">
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
