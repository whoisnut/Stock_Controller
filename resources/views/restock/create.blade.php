@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-plus-circle me-2 text-primary"></i>
                បង្កើតការបញ្ជាទិញស្តុកថ្មី
            </h1>
            <p class="text-muted mb-0">បង្កើតការបញ្ជាទិញទំនិញពីអ្នកផ្គត់ផ្គង់សម្រាប់បំពេញស្តុក</p>
        </div>
        <a href="{{ route('restock.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅការបញ្ជាទិញ
        </a>
    </div>

    <div class="row g-4">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clipboard-data text-primary me-2"></i>
                        ព័ត៌មានការបញ្ជាទិញ
                    </h5>
                </div>
                <div class="card-body pt-3">
                    <form method="POST" action="{{ route('restock.store') }}" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Inventory Item Selection -->
                        <div class="mb-4">
                            <label for="inventory_item_id" class="form-label fw-semibold">
                                ទំនិញក្នុងស្តុក <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-box text-muted"></i>
                                </span>
                                <select class="form-select border-start-0 @error('inventory_item_id') is-invalid @enderror" 
                                        id="inventory_item_id" name="inventory_item_id" required>
                                    <option value="">ជ្រើសរើសទំនិញដែលត្រូវបំពេញស្តុក</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" 
                                                data-supplier="{{ $item->supplier_id ?? '' }}"
                                                data-price="{{ $item->price }}"
                                                data-current="{{ $item->current_stock }}"
                                                data-minimum="{{ $item->minimum_stock }}"
                                                data-name="{{ $item->name }}"
                                                data-sku="{{ $item->sku }}"
                                                {{ old('inventory_item_id', request('item_id')) == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} ({{ $item->sku }}) - ស្តុកបច្ចុប្បន្ន: {{ $item->current_stock }}
                                            @if($item->needsRestock())
                                                - ស្តុកតិច
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('inventory_item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                ជ្រើសរើសទំនិញដែលអ្នកចង់បញ្ជាទិញបន្ថែម
                            </div>
                        </div>
                        
                        <!-- Supplier Selection -->
                        <div class="mb-4">
                            <label for="supplier_id" class="form-label fw-semibold">
                                អ្នកផ្គត់ផ្គង់ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-people text-muted"></i>
                                </span>
                                <select class="form-select border-start-0 @error('supplier_id') is-invalid @enderror" 
                                        id="supplier_id" name="supplier_id" required>
                                    <option value="">ជ្រើសរើសអ្នកផ្គត់ផ្គង់</option>
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
                        </div>
                        
                        <!-- Quantity and Cost Row -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="quantity_ordered" class="form-label fw-semibold">
                                        បរិមាណដែលត្រូវបញ្ជាទិញ <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-123 text-muted"></i>
                                        </span>
                                        <input type="number" 
                                               class="form-control border-start-0 @error('quantity_ordered') is-invalid @enderror" 
                                               id="quantity_ordered" 
                                               name="quantity_ordered" 
                                               value="{{ old('quantity_ordered') }}" 
                                               min="1" 
                                               required>
                                        @error('quantity_ordered')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div id="quantity-suggestion" class="form-text"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="total_cost" class="form-label fw-semibold">
                                        តម្លៃសរុប <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">$</span>
                                        <input type="number" 
                                               class="form-control border-start-0 @error('total_cost') is-invalid @enderror" 
                                               id="total_cost" 
                                               name="total_cost" 
                                               value="{{ old('total_cost') }}" 
                                               min="0" 
                                               step="0.01" 
                                               required>
                                        @error('total_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div id="cost-calculation" class="form-text"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date Row -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="order_date" class="form-label fw-semibold">
                                        កាលបរិច្ឆេទបញ្ជាទិញ <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-calendar text-muted"></i>
                                        </span>
                                        <input type="date" 
                                               class="form-control border-start-0 @error('order_date') is-invalid @enderror" 
                                               id="order_date" 
                                               name="order_date" 
                                               value="{{ old('order_date', '2025-06-17') }}" 
                                               required>
                                        @error('order_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="expected_date" class="form-label fw-semibold">
                                        កាលបរិច្ឆេទរំពឹងទុកនឹងទទួល
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-calendar-check text-muted"></i>
                                        </span>
                                        <input type="date" 
                                               class="form-control border-start-0 @error('expected_date') is-invalid @enderror" 
                                               id="expected_date" 
                                               name="expected_date" 
                                               value="{{ old('expected_date') }}">
                                        @error('expected_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        កាលបរិច្ឆេទដែលរំពឹងថានឹងទទួលបានទំនិញ
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('restock.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>
                                បោះបង់
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>
                                បង្កើតការបញ្ជាទិញ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Order Summary Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clipboard-check text-info me-2"></i>
                        សេចក្តីសង្ខេបការបញ្ជាទិញ
                    </h5>
                </div>
                <div class="card-body">
                    <div id="item-details" style="display: none;">
                        <div class="mb-3">
                            <h6 class="text-primary mb-2">
                                <i class="bi bi-box me-1"></i>
                                ទំនិញដែលបានជ្រើសរើស:
                            </h6>
                            <div id="item-info" class="p-3 bg-light rounded"></div>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-warning mb-2">
                                <i class="bi bi-graph-up me-1"></i>
                                ស្ថានភាពស្តុក:
                            </h6>
                            <div id="stock-info" class="p-3 bg-light rounded"></div>
                        </div>
                    </div>
                    
                    <div id="no-selection" class="text-center py-4">
                        <i class="bi bi-arrow-up text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0">ជ្រើសរើសទំនិញខាងលើដើម្បីមើលព័ត៌មានលម្អិត</p>
                    </div>
                </div>
            </div>
            
            <!-- Tips Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightbulb text-warning me-2"></i>
                        គន្លឹះសម្រាប់ការបញ្ជាទិញ
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="text-primary mb-2">គោលការណ៍ណែនាំការបញ្ជាទិញ:</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            បញ្ជាទិញបរិមាណគ្រប់គ្រាន់រហូតដល់ការដឹកជញ្ជូនបន្ទាប់
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            ពិចារណាចំនួនអប្បបរមាបញ្ជាទិញរបស់អ្នកផ្គត់ផ្គង់
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            គិតគូរពីដែនកំណត់កន្លែងផ្ទុក
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            កំណត់ការរំពឹងទុកការដឹកជញ្ជូនដោយសមរម្យ
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Quick Stats Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up text-success me-2"></i>
                        ស្ថិតិរហ័ស
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-primary mb-1">{{ $items->count() }}</h4>
                                <small class="text-muted">ទំនិញសរុប</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-warning mb-1">{{ $items->filter(function($item) { return $item->needsRestock(); })->count() }}</h4>
                                <small class="text-muted">ស្តុកតិច</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex align-items-center text-muted small">
                            <i class="bi bi-person me-2"></i>
                            <span>អ្នកប្រើ: <strong>whoisnut</strong></span>
                        </div>
                        <div class="d-flex align-items-center text-muted small mt-1">
                            <i class="bi bi-clock me-2"></i>
                            <span>17/06/2025 10:25</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Form Styling */
.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.input-group-text {
    background-color: #f8f9fa !important;
    border-color: #e9ecef;
}

.form-control.border-start-0:focus,
.form-select.border-start-0:focus {
    border-left: 1px solid #0d6efd;
}

.input-group:focus-within .input-group-text {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.05) !important;
}

.input-group:focus-within .input-group-text i {
    color: #0d6efd !important;
}

/* Card Enhancements */
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
}

/* Button Enhancements */
.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Khmer Font Support */
body {
    font-family: 'Noto Sans Khmer', 'Khmer OS', sans-serif;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

/* Custom spacing for better Khmer text rendering */
.form-text,
.small {
    line-height: 1.6;
}

/* Text colors */
.text-gray-800 {
    color: #374151 !important;
}

/* Validation styling */
.was-validated .form-control:valid,
.form-control.is-valid,
.was-validated .form-select:valid,
.form-select.is-valid {
    border-color: #198754;
}

.was-validated .form-control:invalid,
.form-control.is-invalid,
.was-validated .form-select:invalid,
.form-select.is-invalid {
    border-color: #dc3545;
}

/* Loading state */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Background for info sections */
.bg-light {
    background-color: #f8f9fa !important;
}
</style>

<!-- Enhanced JavaScript -->
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
            const price = parseFloat(selectedOption.dataset.price) || 0;
            const current = parseInt(selectedOption.dataset.current) || 0;
            const minimum = parseInt(selectedOption.dataset.minimum) || 0;
            const itemName = selectedOption.dataset.name;
            const itemSku = selectedOption.dataset.sku;
            const needed = Math.max(0, minimum - current);
            const suggested = needed > 0 ? needed * 2 : minimum; // Suggest double the shortage or minimum stock

            // Show item details
            itemDetails.style.display = 'block';
            noSelection.style.display = 'none';

            // Update item info
            itemInfo.innerHTML = `
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-box text-primary me-2"></i>
                    <strong>${itemName}</strong>
                </div>
                <div class="small text-muted">
                    <div>SKU: ${itemSku}</div>
                    <div>តម្លៃ: $${price.toFixed(2)} ក្នុងមួយឯកតា</div>
                </div>
            `;

            // Update stock info
            const stockStatus = current <= minimum ? 'ស្តុកតិច' : 'មានស្តុក';
            const stockBadge = current <= minimum ? 'bg-danger' : 'bg-success';
            const stockIcon = current <= minimum ? 'bi-exclamation-triangle' : 'bi-check-circle';
            
            stockInfo.innerHTML = `
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <small class="text-muted">បច្ចុប្បន្ន:</small>
                        <div class="fw-bold">${current.toLocaleString()}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">អប្បបរមា:</small>
                        <div class="fw-bold">${minimum.toLocaleString()}</div>
                    </div>
                </div>
                <span class="badge ${stockBadge}">
                    <i class="${stockIcon} me-1"></i>${stockStatus}
                </span>
            `;

            // Update quantity suggestion
            if (needed > 0) {
                quantitySuggestion.innerHTML = `
                    <div class="alert alert-warning alert-sm p-2 mb-0">
                        <i class="bi bi-lightbulb me-1"></i> 
                        ណែនាំ: ${suggested.toLocaleString()} (គ្របដណ្តប់កង្វះខាត + បំរុង)
                    </div>
                `;
                quantityInput.value = suggested;
            } else {
                quantitySuggestion.innerHTML = `
                    <div class="alert alert-info alert-sm p-2 mb-0">
                        <i class="bi bi-info-circle me-1"></i> 
                        ណែនាំ: ${suggested.toLocaleString()} (បំពេញស្តុកស្តង់ដារ)
                    </div>
                `;
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
            costCalculation.innerHTML = `
                <div class="alert alert-success alert-sm p-2 mb-0">
                    <i class="bi bi-calculator me-1"></i> 
                    តម្លៃប៉ាន់ស្មាន: $${estimated.toFixed(2)} (${quantity.toLocaleString()} × $${unitPrice.toFixed(2)})
                </div>
            `;
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
            const price = parseFloat(selectedOption.dataset.price) || 0;
            updateCostCalculation(price);
        }
    });

    // Initialize if item is pre-selected
    if (itemSelect.value) {
        updateItemDetails();
    }

    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);

    // Auto-suggest expected delivery date (7 days from order date)
    const orderDateInput = document.getElementById('order_date');
    const expectedDateInput = document.getElementById('expected_date');
    
    orderDateInput.addEventListener('change', function() {
        if (this.value && !expectedDateInput.value) {
            const orderDate = new Date(this.value);
            orderDate.setDate(orderDate.getDate() + 7);
            expectedDateInput.value = orderDate.toISOString().split('T')[0];
        }
    });
});
</script>

@endsection