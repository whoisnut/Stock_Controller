@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-plus-circle"></i> Create Restock Order</h2>
            <a href="{{ route('restock.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('restock.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="inventory_item_id" class="form-label">Inventory Item *</label>
                        <select class="form-select @error('inventory_item_id') is-invalid @enderror" 
                                id="inventory_item_id" name="inventory_item_id" required>
                            <option value="">Select an item to restock</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" 
                                        data-supplier="{{ $item->supplier_id ?? '' }}"
                                        data-price="{{ $item->price }}"
                                        data-current="{{ $item->current_stock }}"
                                        data-minimum="{{ $item->minimum_stock }}"
                                        {{ old('inventory_item_id', request('item_id')) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} ({{ $item->sku }}) - Current: {{ $item->current_stock }}
                                    @if($item->needsRestock())
                                        - LOW STOCK
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('inventory_item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier *</label>
                        <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                id="supplier_id" name="supplier_id" required>
                            <option value="">Select a supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                    @if($supplier->contact_person)
                                        ({{ $supplier->contact_person }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity_ordered" class="form-label">Quantity to Order *</label>
                                <input type="number" class="form-control @error('quantity_ordered') is-invalid @enderror" 
                                       id="quantity_ordered" name="quantity_ordered" value="{{ old('quantity_ordered') }}" 
                                       min="1" required>
                                @error('quantity_ordered')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="quantity-suggestion" class="form-text"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="total_cost" class="form-label">Total Cost *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('total_cost') is-invalid @enderror" 
                                           id="total_cost" name="total_cost" value="{{ old('total_cost') }}" 
                                           min="0" step="0.01" required>
                                </div>
                                @error('total_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="cost-calculation" class="form-text"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order_date" class="form-label">Order Date *</label>
                                <input type="date" class="form-control @error('order_date') is-invalid @enderror" 
                                       id="order_date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required>
                                @error('order_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expected_date" class="form-label">Expected Delivery Date</label>
                                <input type="date" class="form-control @error('expected_date') is-invalid @enderror" 
                                       id="expected_date" name="expected_date" value="{{ old('expected_date') }}">
                                @error('expected_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('restock.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Create Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Order Summary</h5>
            </div>
            <div class="card-body">
                <div id="item-details" style="display: none;">
                    <h6>Selected Item:</h6>
                    <div id="item-info"></div>
                    
                    <h6 class="mt-3">Stock Status:</h6>
                    <div id="stock-info"></div>
                </div>
                
                <div id="no-selection" class="text-muted">
                    <i class="bi bi-arrow-up"></i> Select an item above to see details
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Tips</h5>
            </div>
            <div class="card-body">
                <h6>Ordering Guidelines:</h6>
                <ul class="small">
                    <li>Order enough to last until next delivery</li>
                    <li>Consider supplier minimum order quantities</li>
                    <li>Factor in storage space limitations</li>
                    <li>Set realistic delivery expectations</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('inventory_item_id');
    const supplierSelect = document.getElementById('supplier_id');
    const quantityInput = document.getElementById('quantity_ordered');
    const costInput = document.getElementById('total_cost');
    const itemDetails = document.getElementById('item-details');
    const noSelection = document.getElementById('no-selection');
    const itemInfo = document.getElementById('item-info');
    const stockInfo = document.getElementById('stock-info');
    const quantitySuggestion = document.getElementById('quantity-suggestion');
    const costCalculation = document.getElementById('cost-calculation');

    function updateItemDetails() {
        const selectedOption = itemSelect.selectedOptions[0];
        if (selectedOption && selectedOption.value) {
            const supplierId = selectedOption.dataset.supplier;
            const price = parseFloat(selectedOption.dataset.price);
            const current = parseInt(selectedOption.dataset.current);
            const minimum = parseInt(selectedOption.dataset.minimum);
            const needed = Math.max(0, minimum - current);
            const suggested = needed > 0 ? needed * 2 : minimum; // Suggest double the shortage or minimum stock

            // Show item details
            itemDetails.style.display = 'block';
            noSelection.style.display = 'none';

            // Update item info
            itemInfo.innerHTML = `
                <strong>${selectedOption.textContent.split(' (')[0]}</strong><br>
                <small class="text-muted">Price: $${price.toFixed(2)} each</small>
            `;

            // Update stock info
            const stockStatus = current <= minimum ? 'Low Stock' : 'In Stock';
            const stockBadge = current <= minimum ? 'bg-danger' : 'bg-success';
            stockInfo.innerHTML = `
                Current: ${current}<br>
                Minimum: ${minimum}<br>
                <span class="badge ${stockBadge}">${stockStatus}</span>
            `;

            // Update quantity suggestion
            if (needed > 0) {
                quantitySuggestion.innerHTML = `<i class="bi bi-lightbulb text-warning"></i> Suggested: ${suggested} (covers shortage + buffer)`;
                quantityInput.value = suggested;
            } else {
                quantitySuggestion.innerHTML = `<i class="bi bi-info-circle text-info"></i> Suggested: ${suggested} (standard restock)`;
            }

            // Auto-select supplier if item has one
            if (supplierId) {
                supplierSelect.value = supplierId;
            }

            // Update cost calculation
            updateCostCalculation(price);
        } else {
            itemDetails.style.display = 'none';
            noSelection.style.display = 'block';
            quantitySuggestion.innerHTML = '';
            costCalculation.innerHTML = '';
        }
    }

    function updateCostCalculation(unitPrice) {
        const quantity = parseInt(quantityInput.value) || 0;
        if (quantity > 0 && unitPrice > 0) {
            const estimated = quantity * unitPrice;
            costCalculation.innerHTML = `<i class="bi bi-calculator"></i> Estimated: $${estimated.toFixed(2)} (${quantity} × $${unitPrice.toFixed(2)})`;
            costInput.value = estimated.toFixed(2);
        } else {
            costCalculation.innerHTML = '';
        }
    }

    // Event listeners
    itemSelect.addEventListener('change', updateItemDetails);
    
    quantityInput.addEventListener('input', function() {
        const selectedOption = itemSelect.selectedOptions[0];
        if (selectedOption && selectedOption.value) {
            const price = parseFloat(selectedOption.dataset.price);
            updateCostCalculation(price);
        }
    });

    // Initialize if item is pre-selected
    if (itemSelect.value) {
        updateItemDetails();
    }
});
</script>
@endsection6