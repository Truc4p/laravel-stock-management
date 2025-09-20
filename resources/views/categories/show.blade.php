@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-tags me-2"></i>Category Details
    </h1>
    <div class="btn-group" role="group">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Edit Category
        </a>
    </div>
</div>

<div class="row mb-4">
    <!-- Category Information -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Category Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Category Name</label>
                            <p class="fw-bold fs-5">{{ $category->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Slug</label>
                            <p><code class="fs-6">{{ $category->slug }}</code></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <p>
                                <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }} fs-6">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        @if($category->description)
                        <div class="mb-3">
                            <label class="form-label text-muted">Description</label>
                            <p>{{ $category->description }}</p>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Products Count</label>
                            <p class="fw-bold fs-8">
                                <span class="badge bg-info fs-8">{{ $category->products->count() }} products</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                @php
                    $totalValue = $category->products->sum(function($product) {
                        return $product->inventory->sum(function($inventory) use ($product) {
                            return $inventory->quantity * $product->cost_price;
                        });
                    });
                @endphp
                
                @if($totalValue > 0)
                <div class="mb-3">
                    <label class="form-label text-muted">Total Category Value</label>
                    <p class="fw-bold text-success fs-4">${{ number_format($totalValue, 2) }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Quick Stats
                </h5>
            </div>
            <div class="card-body">
                @php
                    $activeProducts = $category->products->where('is_active', true)->count();
                    $totalStock = $category->products->sum(function($product) {
                        return $product->inventory->sum('quantity');
                    });
                @endphp
                
                <div class="mb-3">
                    <p class="fw-bold fs-8 mb-0">{{ $category->products->count() }} Total Products</p>
                </div>
                
                <div class="mb-3">
                    <p class="fw-bold text-success fs-8 mb-0">{{ $activeProducts }} Active Products</p>
                </div>
                
                <div class="mb-3">
                    <p class="fw-bold text-primary fs-8 mb-0">{{ number_format($totalStock) }} Total Stock Units</p>
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
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Category
                    </a>
                    <a href="{{ route('products.create') }}?category={{ $category->id }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Add Product
                    </a>
                    <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="btn btn-info">
                        <i class="fas fa-box me-2"></i>View All Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products in Category -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-box me-2"></i>Products in this Category
            <span class="badge bg-primary ms-2">{{ $category->products->count() }} products</span>
        </h5>
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
                                        <th width="140">Actions</th>
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
                                            <strong>${{ number_format($product->cost_price, 2) }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $totalStock = $product->inventory->sum('quantity');
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
                                            <strong>${{ number_format($totalStock * $product->cost_price, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="btn btn-info btn-sm me-1" title="View Product">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="btn btn-warning btn-sm" title="Edit Product">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: white;">
                                        <th colspan="5">Total Category Value:</th>
                                        <th>
                                            ${{ number_format($category->products->sum(function($product) {
                                                return $product->inventory->sum(function($inventory) use ($product) {
                                                    return $inventory->quantity * $product->cost_price;
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
