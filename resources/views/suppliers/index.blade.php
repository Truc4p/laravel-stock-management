@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-truck me-2"></i>Suppliers Management
                    </h4>
                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Supplier
                    </a>
                </div>
                <div class="card-body">
                    @if($suppliers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Supplier Name</th>
                                        <th>Contact Person</th>
                                        <th>Contact Info</th>
                                        <th>Location</th>
                                        <th>Products Count</th>
                                        <th>Status</th>
                                        <th width="140">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suppliers as $supplier)
                                    <tr>
                                        <td>
                                            <strong>{{ $supplier->name }}</strong>
                                            @if($supplier->notes)
                                                <br><small class="text-muted">{{ Str::limit($supplier->notes, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($supplier->contact_person)
                                                <span>{{ $supplier->contact_person }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small">
                                                @if($supplier->email)
                                                    <div><i class="fas fa-envelope text-muted me-1"></i>{{ $supplier->email }}</div>
                                                @endif
                                                @if($supplier->phone)
                                                    <div><i class="fas fa-phone text-muted me-1"></i>{{ $supplier->phone }}</div>
                                                @endif
                                                @if(!$supplier->email && !$supplier->phone)
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($supplier->city || $supplier->country)
                                                <div class="small">
                                                    <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                    {{ $supplier->city }}@if($supplier->city && $supplier->country), @endif{{ $supplier->country }}
                                                    @if($supplier->postal_code)
                                                        <br><span class="text-muted">{{ $supplier->postal_code }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $supplier->products_count }} product{{ $supplier->products_count !== 1 ? 's' : '' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($supplier->is_active)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times me-1"></i>Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('suppliers.show', $supplier) }}" 
                                               class="btn btn-info btn-sm me-1" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('suppliers.edit', $supplier) }}" 
                                               class="btn btn-warning btn-sm me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($supplier->products_count == 0)
                                                <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" 
                                                      class="d-inline" onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-danger btn-sm" disabled title="Cannot delete supplier with products">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $suppliers->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No suppliers found</h5>
                            <p class="text-muted">Get started by adding your first supplier.</p>
                            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add New Supplier
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
