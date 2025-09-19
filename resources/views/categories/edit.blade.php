@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Category: {{ $category->name }}
                    </h4>
                    <div>
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-info me-2">
                            <i class="fas fa-eye me-2"></i>View
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Categories
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('categories.update', $category) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $category->name) }}" 
                                           required
                                           placeholder="e.g., Electronics, Clothing, Food & Beverages">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" 
                                           class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" 
                                           name="slug" 
                                           value="{{ old('slug', $category->slug) }}"
                                           placeholder="e.g., electronics, clothing">
                                    <div class="form-text">URL-friendly version of the name. Leave blank to auto-generate from name.</div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4"
                                              placeholder="Brief description of this category and what products it includes">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1" 
                                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Category
                                        </label>
                                    </div>
                                    <div class="form-text">Inactive categories won't be available when creating new products</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="fas fa-info-circle me-2"></i>Category Statistics
                                        </h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-primary mb-0">{{ $category->products()->count() }}</div>
                                                    <small class="text-muted">Products</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-success mb-0">{{ $category->created_at->format('M Y') }}</div>
                                                    <small class="text-muted">Created</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="fas fa-lightbulb me-2"></i>Tips
                                        </h6>
                                        <ul class="list-unstyled small text-muted mb-0">
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Changing the name will auto-update the slug if it matches the old name
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Deactivating a category won't affect existing products
                                            </li>
                                            <li class="mb-0">
                                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                                Categories with products cannot be deleted
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        @if($category->products()->count() == 0)
                                            <form method="POST" action="{{ route('categories.destroy', $category) }}" 
                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash me-2"></i>Delete Category
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Cannot delete category with existing products
                                            </span>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Update Category
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Auto-generate slug from name if current slug matches the pattern of the original name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slugField = document.getElementById('slug');
        const originalName = "{{ $category->name }}";
        const originalSlug = "{{ $category->slug }}";
        
        // Check if current slug looks like it was auto-generated from original name
        const expectedSlug = originalName.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        
        // Only auto-generate if current slug matches the expected pattern
        if (slugField.value === originalSlug && originalSlug === expectedSlug) {
            const newSlug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            
            slugField.value = newSlug;
        }
    });
</script>
@endsection
@endsection
