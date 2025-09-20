@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-edit me-2"></i>Edit Product
    </h1>
    <div class="btn-group" role="group">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Products
        </a>
        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info">
            <i class="fas fa-eye me-2"></i>View Product
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-box me-2"></i>Product Information
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">Basic Information</h6>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU *</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                               id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Supplier *</label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                        id="supplier_id" name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Stock -->
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">Pricing & Stock</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cost_price" class="form-label">Cost Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('cost_price') is-invalid @enderror" 
                                           id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" required>
                                </div>
                                @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="selling_price" class="form-label">Selling Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror" 
                                           id="selling_price" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required>
                                </div>
                                @error('selling_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="unit" class="form-label">Unit *</label>
                                <select class="form-select @error('unit') is-invalid @enderror" 
                                        id="unit" name="unit" required>
                                    <option value="pcs" {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>Pieces</option>
                                    <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilograms</option>
                                    <option value="g" {{ old('unit', $product->unit) == 'g' ? 'selected' : '' }}>Grams</option>
                                    <option value="liter" {{ old('unit', $product->unit) == 'liter' ? 'selected' : '' }}>Liters</option>
                                    <option value="ml" {{ old('unit', $product->unit) == 'ml' ? 'selected' : '' }}>Milliliters</option>
                                    <option value="bottle" {{ old('unit', $product->unit) == 'bottle' ? 'selected' : '' }}>Bottle</option>
                                    <option value="box" {{ old('unit', $product->unit) == 'box' ? 'selected' : '' }}>Box</option>
                                    <option value="pack" {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="minimum_stock" class="form-label">Minimum Stock *</label>
                                <input type="number" class="form-control @error('minimum_stock') is-invalid @enderror" 
                                       id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" required>
                                @error('minimum_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="maximum_stock" class="form-label">Maximum Stock</label>
                        <input type="number" class="form-control @error('maximum_stock') is-invalid @enderror" 
                               id="maximum_stock" name="maximum_stock" value="{{ old('maximum_stock', $product->maximum_stock) }}">
                        @error('maximum_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                               id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}">
                        @error('barcode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Storage Location</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location', $product->location) }}" 
                               placeholder="e.g., A1-B2, Shelf 3">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="Additional notes about this product">{{ old('notes', $product->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                               value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active Product
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Product
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Product Information Summary -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-info-circle me-2"></i>Current Product Summary
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="text-center p-3 bg-light rounded">
                    <h6 class="text-muted mb-1">Current Cost</h6>
                    <h4 class="text-success mb-0">${{ number_format($product->cost_price, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-3 bg-light rounded">
                    <h6 class="text-muted mb-1">Current Selling</h6>
                    <h4 class="text-primary mb-0">${{ number_format($product->selling_price, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-3 bg-light rounded">
                    <h6 class="text-muted mb-1">Profit Margin</h6>
                    @php
                        $margin = $product->cost_price > 0 ? (($product->selling_price - $product->cost_price) / $product->cost_price) * 100 : 0;
                    @endphp
                    <h4 class="mb-0 {{ $margin > 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($margin, 1) }}%
                    </h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-3 bg-light rounded">
                    <h6 class="text-muted mb-1">Current Stock</h6>
                    @php
                        $totalStock = $product->inventory->sum('quantity') ?? 0;
                    @endphp
                    <h4 class="mb-0 {{ $totalStock < $product->minimum_stock ? 'text-danger' : 'text-success' }}">
                        {{ $totalStock }} {{ $product->unit }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate and display profit margin dynamically
    const costPriceInput = document.getElementById('cost_price');
    const sellingPriceInput = document.getElementById('selling_price');
    
    function calculateMargin() {
        const costPrice = parseFloat(costPriceInput.value) || 0;
        const sellingPrice = parseFloat(sellingPriceInput.value) || 0;
        
        if (costPrice > 0) {
            const margin = ((sellingPrice - costPrice) / costPrice) * 100;
            console.log(`Profit Margin: ${margin.toFixed(1)}%`);
        }
    }
    
    costPriceInput.addEventListener('input', calculateMargin);
    sellingPriceInput.addEventListener('input', calculateMargin);
});
</script>
@endsection
