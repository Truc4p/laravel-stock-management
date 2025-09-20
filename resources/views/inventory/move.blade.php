@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-exchange-alt me-2"></i>Stock Movement
    </h1>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-plus me-2"></i>Record Stock Movement
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('inventory.process-movement') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">Product & Location</h6>
                    
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Product *</label>
                        <select class="form-select @error('product_id') is-invalid @enderror" 
                                id="product_id" name="product_id" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}
                                        data-sku="{{ $product->sku }}" 
                                        data-unit="{{ $product->unit }}"
                                        data-cost="{{ $product->cost_price }}">
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="warehouse_id" class="form-label">Warehouse *</label>
                        <select class="form-select @error('warehouse_id') is-invalid @enderror" 
                                id="warehouse_id" name="warehouse_id" required>
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }} ({{ $warehouse->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Movement Type *</label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" name="type" required>
                            <option value="">Select Movement Type</option>
                            <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>
                                Stock In (Receiving/Purchase)
                            </option>
                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>
                                Stock Out (Sale/Transfer)
                            </option>
                            <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>
                                Stock Adjustment (Count/Correction)
                            </option>
                            <option value="transfer" {{ old('type') == 'transfer' ? 'selected' : '' }}>
                                Transfer Between Warehouses
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="text-primary mb-3">Movement Details</h6>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity *</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                            <span class="input-group-text" id="unit-display">units</span>
                        </div>
                        <div class="form-text">
                            For adjustments: Use positive numbers to increase stock, negative to decrease.
                        </div>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="unit_cost" class="form-label">Unit Cost</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control @error('unit_cost') is-invalid @enderror" 
                                   id="unit_cost" name="unit_cost" value="{{ old('unit_cost') }}">
                        </div>
                        <div class="form-text">
                            Required for stock-in movements to calculate average cost.
                        </div>
                        @error('unit_cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reference_number" class="form-label">Reference Number</label>
                        <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                               id="reference_number" name="reference_number" value="{{ old('reference_number') }}"
                               placeholder="PO#, Invoice#, Transfer#, etc.">
                        @error('reference_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <input type="text" class="form-control @error('reason') is-invalid @enderror" 
                               id="reason" name="reason" value="{{ old('reason') }}"
                               placeholder="Purchase, Sale, Damaged, Expired, etc.">
                        @error('reason')
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
                                  placeholder="Additional details about this stock movement">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Movement Summary -->
            <div class="row" id="movement-summary" style="display: none;">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Movement Summary</h6>
                        <div id="summary-content"></div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Process Movement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const typeSelect = document.getElementById('type');
    const quantityInput = document.getElementById('quantity');
    const unitCostInput = document.getElementById('unit_cost');
    const unitDisplay = document.getElementById('unit-display');
    const summaryDiv = document.getElementById('movement-summary');
    const summaryContent = document.getElementById('summary-content');

    // Update unit display when product changes
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const unit = selectedOption.dataset.unit;
            const cost = selectedOption.dataset.cost;
            unitDisplay.textContent = unit;
            
            // Pre-fill cost for stock-in movements
            if (typeSelect.value === 'in' && !unitCostInput.value) {
                unitCostInput.value = cost;
            }
        } else {
            unitDisplay.textContent = 'units';
        }
        updateSummary();
    });

    // Update summary when inputs change
    [typeSelect, quantityInput, unitCostInput].forEach(input => {
        input.addEventListener('input', updateSummary);
    });

    function updateSummary() {
        const product = productSelect.options[productSelect.selectedIndex];
        const type = typeSelect.value;
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitCost = parseFloat(unitCostInput.value) || 0;

        if (!product.value || !type || !quantity) {
            summaryDiv.style.display = 'none';
            return;
        }

        const unit = product.dataset.unit;
        const productName = product.text;
        let summaryText = `<strong>${productName}</strong><br>`;
        
        if (type === 'in') {
            summaryText += `Adding <strong>${quantity} ${unit}</strong> to inventory`;
            if (unitCost > 0) {
                summaryText += `<br>Total value: <strong>$${(quantity * unitCost).toFixed(2)}</strong>`;
            }
        } else if (type === 'out') {
            summaryText += `Removing <strong>${quantity} ${unit}</strong> from inventory`;
        } else if (type === 'adjustment') {
            const action = quantity >= 0 ? 'Adding' : 'Removing';
            summaryText += `${action} <strong>${Math.abs(quantity)} ${unit}</strong> (adjustment)`;
        } else if (type === 'transfer') {
            summaryText += `Transferring <strong>${quantity} ${unit}</strong>`;
        }

        summaryContent.innerHTML = summaryText;
        summaryDiv.style.display = 'block';
    }

    // Show/hide unit cost based on movement type
    typeSelect.addEventListener('change', function() {
        const costGroup = unitCostInput.closest('.mb-3');
        if (this.value === 'in') {
            costGroup.style.display = 'block';
            unitCostInput.required = true;
        } else {
            costGroup.style.display = 'block'; // Keep visible but not required
            unitCostInput.required = false;
        }
        updateSummary();
    });
});
</script>
@endsection
