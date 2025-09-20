@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Warehouse: {{ $warehouse->name }}
                    </h4>
                    <div>
                        <a href="{{ route('warehouses.show', $warehouse) }}" class="btn btn-info me-2">
                            <i class="fas fa-eye me-2"></i>View
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('warehouses.update', $warehouse) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Warehouse Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $warehouse->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="code" class="form-label">Warehouse Code <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           id="code" 
                                           name="code" 
                                           value="{{ old('code', $warehouse->code) }}" 
                                           placeholder="e.g., WH001"
                                           required>
                                    <div class="form-text">Unique identifier for this warehouse</div>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3"
                                              placeholder="Brief description of the warehouse">{{ old('description', $warehouse->description) }}</textarea>
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
                                               {{ old('is_active', $warehouse->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Warehouse
                                        </label>
                                    </div>
                                    <div class="form-text">Inactive warehouses won't be available for new inventory</div>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Location Information
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" 
                                              name="address" 
                                              rows="3"
                                              required
                                              placeholder="Full address of the warehouse">{{ old('address', $warehouse->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('city') is-invalid @enderror" 
                                                   id="city" 
                                                   name="city" 
                                                   value="{{ old('city', $warehouse->city) }}" 
                                                   required>
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="postal_code" class="form-label">Postal Code <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('postal_code') is-invalid @enderror" 
                                                   id="postal_code" 
                                                   name="postal_code" 
                                                   value="{{ old('postal_code', $warehouse->postal_code) }}" 
                                                   required>
                                            @error('postal_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('country') is-invalid @enderror" 
                                           id="country" 
                                           name="country" 
                                           value="{{ old('country', $warehouse->country) }}" 
                                           required>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-address-book me-2"></i>Contact Information
                                </h6>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="manager_name" class="form-label">Manager Name</label>
                                    <input type="text" 
                                           class="form-control @error('manager_name') is-invalid @enderror" 
                                           id="manager_name" 
                                           name="manager_name" 
                                           value="{{ old('manager_name', $warehouse->manager_name) }}">
                                    @error('manager_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">Contact Phone</label>
                                    <input type="text" 
                                           class="form-control @error('contact_phone') is-invalid @enderror" 
                                           id="contact_phone" 
                                           name="contact_phone" 
                                           value="{{ old('contact_phone', $warehouse->contact_phone) }}"
                                           placeholder="+1234567890">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">Contact Email</label>
                                    <input type="email" 
                                           class="form-control @error('contact_email') is-invalid @enderror" 
                                           id="contact_email" 
                                           name="contact_email" 
                                           value="{{ old('contact_email', $warehouse->contact_email) }}"
                                           placeholder="warehouse@company.com">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        @if($warehouse->inventory()->count() == 0)
                                            <form method="POST" action="{{ route('warehouses.destroy', $warehouse) }}" 
                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this warehouse?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash me-2"></i>Delete Warehouse
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Cannot delete warehouse with existing inventory
                                            </span>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Update Warehouse
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
@endsection
