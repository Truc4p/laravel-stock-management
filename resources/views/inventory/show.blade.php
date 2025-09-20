@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-history me-2"></i>Inventory History
    </h1>
    <div class="btn-group" role="group">
        <a href="{{ route('inventory.move') }}" class="btn btn-primary">
            <i class="fas fa-exchange-alt me-2"></i>Stock Movement
        </a>
    </div>
</div>

<div class="row">
    <!-- Inventory Item Information -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Inventory Item Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Product</label>
                            <p class="fw-bold fs-5">{{ $inventory->product->name }}</p>
                            <p><code class="fs-6">{{ $inventory->product->sku }}</code></p>
                            <p><span class="badge bg-info">{{ $inventory->product->category->name }}</span></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Warehouse</label>
                            <p class="fw-semibold">{{ $inventory->warehouse->name }}</p>
                            <p class="text-muted">{{ $inventory->warehouse->code }} - {{ $inventory->warehouse->location }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Current Stock</label>
                            <p class="fw-semibold">
                                <span class="badge bg-{{ $inventory->quantity <= $inventory->product->minimum_stock ? 'danger' : 'success' }}" fs-6">
                                    {{ number_format($inventory->quantity) }} {{ $inventory->product->unit }}
                                </span>
                            </p>
                            @if($inventory->quantity <= $inventory->product->minimum_stock)
                                <small class="text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Below minimum stock level!
                                </small>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Reserved Quantity</label>
                            <p class="fw-semibold">
                                <span class="badge bg-warning" fs-6">
                                    {{ number_format($inventory->reserved_quantity) }} {{ $inventory->product->unit }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Available Quantity</label>
                            <p class="fw-semibold">
                                <span class="badge bg-primary" fs-6">
                                    {{ number_format($inventory->available_quantity) }} {{ $inventory->product->unit }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Average Cost</label>
                            <p class="fw-bold text-success fs-5">${{ number_format($inventory->average_cost, 2) }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Stock Value</label>
                            <p class="fw-bold text-primary fs-5">
                                ${{ number_format($inventory->quantity * $inventory->average_cost, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Last Movement</label>
                            <p class="text-muted">
                                @if($inventory->last_movement_at)
                                    {{ $inventory->last_movement_at->format('F j, Y \a\t g:i A') }}
                                    <br><small>({{ $inventory->last_movement_at->diffForHumans() }})</small>
                                @else
                                    No movements recorded
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Minimum Stock Level</label>
                            <p class="text-muted">
                                {{ number_format($inventory->product->minimum_stock) }} {{ $inventory->product->unit }}
                            </p>
                        </div>
                    </div>
                </div>
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
                <div class="mb-3">
                    <label class="form-label text-muted">Total Movements</label>
                    <p class="fw-bold fs-4">{{ $movements->total() }}</p>
                </div>
                
                @if($movements->total() > 0)
                    @php
                        $inboundTotal = $movements->where('quantity', '>', 0)->sum('quantity');
                        $outboundTotal = abs($movements->where('quantity', '<', 0)->sum('quantity'));
                    @endphp
                    <div class="mb-3">
                        <label class="form-label text-muted">Total Inbound</label>
                        <p class="fw-bold text-success">
                            +{{ number_format($inboundTotal) }} {{ $inventory->product->unit }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Total Outbound</label>
                        <p class="fw-bold text-danger">
                            -{{ number_format($outboundTotal) }} {{ $inventory->product->unit }}
                        </p>
                    </div>
                @endif
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
                    <button type="button" class="btn btn-warning" 
                            data-bs-toggle="modal" data-bs-target="#adjustModal">
                        <i class="fas fa-edit me-2"></i>Adjust Stock
                    </button>
                    <a href="{{ route('products.show', $inventory->product) }}" class="btn btn-info">
                        <i class="fas fa-box me-2"></i>View Product
                    </a>
                    <a href="{{ route('inventory.move') }}" class="btn btn-secondary">
                        <i class="fas fa-exchange-alt me-2"></i>Stock Movement
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Movement History -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-history me-2"></i>Stock Movement History
            <span class="badge bg-primary ms-2">{{ $movements->total() }} movements</span>
        </h5>
    </div>
    <div class="card-body p-2">
        @if($movements->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>Unit Cost</th>
                            <th>Reference</th>
                            <th>User</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $movement)
                        <tr>
                            <td>
                                <div class="text-nowrap">
                                    <strong>{{ $movement->movement_date->format('M j, Y') }}</strong>
                                    <br><small class="text-muted">{{ $movement->movement_date->format('g:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                @php
                                    $typeConfig = [
                                        'in' => ['badge' => 'success', 'icon' => 'arrow-down', 'label' => 'Stock In'],
                                        'out' => ['badge' => 'danger', 'icon' => 'arrow-up', 'label' => 'Stock Out'],
                                        'adjustment' => ['badge' => 'warning', 'icon' => 'edit', 'label' => 'Adjustment'],
                                        'transfer' => ['badge' => 'info', 'icon' => 'exchange-alt', 'label' => 'Transfer']
                                    ];
                                    $config = $typeConfig[$movement->type] ?? ['badge' => 'secondary', 'icon' => 'question', 'label' => ucfirst($movement->type)];
                                @endphp
                                <span class="badge bg-{{ $config['badge'] }}">
                                    <i class="fas fa-{{ $config['icon'] }} me-1"></i>{{ $config['label'] }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold {{ $movement->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $movement->quantity > 0 ? '+' : '' }}{{ number_format($movement->quantity) }} {{ $inventory->product->unit }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted">{{ number_format($movement->quantity_before) }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ number_format($movement->quantity_after) }}</span>
                            </td>
                            <td>
                                @if($movement->unit_cost)
                                    <span class="text-success">${{ number_format($movement->unit_cost, 2) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($movement->reference_number)
                                    <code class="small">{{ $movement->reference_number }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($movement->user)
                                    <span class="small">{{ $movement->user->name }}</span>
                                @else
                                    <span class="text-muted small">System</span>
                                @endif
                            </td>
                            <td>
                                @if($movement->reason)
                                    <span class="small" title="{{ $movement->notes }}">{{ Str::limit($movement->reason, 30) }}</span>
                                    @if($movement->notes)
                                        <i class="fas fa-info-circle text-muted ms-1" title="{{ $movement->notes }}"></i>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($movements->hasPages())
                <div class="card-footer">
                    {{ $movements->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-history fs-1 text-muted mb-3"></i>
                <h5>No stock movements found</h5>
                <p class="text-muted">No stock movements have been recorded for this inventory item yet.</p>
                <a href="{{ route('inventory.move') }}" class="btn btn-primary">
                    <i class="fas fa-exchange-alt me-2"></i>Add Stock Movement
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adjust Stock - {{ $inventory->product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('inventory.adjust', $inventory) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Current Stock</label>
                        <input type="text" class="form-control" 
                               value="{{ $inventory->quantity }} {{ $inventory->product->unit }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="adjustment_type" class="form-label">Adjustment Type</label>
                        <select class="form-select" name="adjustment_type" required>
                            <option value="set">Set to specific quantity</option>
                            <option value="add">Add quantity</option>
                            <option value="subtract">Subtract quantity</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason *</label>
                        <input type="text" class="form-control" name="reason" required 
                               placeholder="e.g., Stock count correction, Damaged items">
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Adjust Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
