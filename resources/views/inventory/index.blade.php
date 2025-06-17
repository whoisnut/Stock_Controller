@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-box me-2 text-primary"></i>
                គ្រប់គ្រងស្តុកទំនិញ
            </h1>
            <p class="text-muted mb-0">គ្រប់គ្រងនិងតាមដានស្តុកទំនិញទាំងអស់របស់អ្នក</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filterSection">
                <i class="bi bi-funnel me-1"></i> តម្រង
            </button>
            <button class="btn btn-outline-info" onclick="exportData()">
                <i class="bi bi-download me-1"></i> នាំចេញ
            </button>
            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> បន្ថែមទំនិញថ្មី
            </a>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-box text-primary"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $items->count() }}</h4>
                            <small class="text-muted">ទំនិញសរុប</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-exclamation-triangle text-warning"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $items->filter(function($item) { return $item->needsRestock(); })->count() }}</h4>
                            <small class="text-muted">ស្តុកតិច</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-success bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $items->filter(function($item) { return !$item->needsRestock(); })->count() }}</h4>
                            <small class="text-muted">ស្តុកគ្រប់គ្រាន់</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-info bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-currency-dollar text-info"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">${{ number_format($items->sum(function($item) { return $item->current_stock * $item->price; }), 0) }}</h4>
                            <small class="text-muted">តម្លៃស្តុកសរុប</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="collapse mb-4" id="filterSection">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">ស្វែងរកតាមឈ្មោះ</label>
                        <input type="text" class="form-control" placeholder="បញ្ចូលឈ្មោះទំនិញ..." id="searchItem">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">តម្រងតាមស្ថានភាព</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">ទាំងអស់</option>
                            <option value="low">ស្តុកតិច</option>
                            <option value="normal">ស្តុកគ្រប់គ្រាន់</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">តម្រងតាមអ្នកផ្គត់ផ្គង់</label>
                        <select class="form-select" id="supplierFilter">
                            <option value="">ទាំងអស់</option>
                            @foreach($items->whereNotNull('supplier')->unique('supplier.name')->pluck('supplier') as $supplier)
                                <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                            @endforeach
                            <option value="no-supplier">គ្មានអ្នកផ្គត់ផ្គង់</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">&nbsp;</label>
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-1"></i> សម្អាត
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Inventory Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-ul text-secondary me-2"></i>
                            បញ្ជីទំនិញក្នុងស្តុក
                        </h5>
                        <small class="text-muted">ការបង្ហាញ: <span id="itemCount">{{ $items->count() }}</span> ទំនិញ</small>
                    </div>
                </div>
                <div class="card-body pt-3">
                    @if($items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>SKU</th>
                                        <th>ឈ្មោះទំនិញ</th>
                                        <th>ស្តុកបច្ចុប្បន្ន</th>
                                        <th>ស្តុកអប្បបរមា</th>
                                        <th>តម្លៃ</th>
                                        <th>អ្នកផ្គត់ផ្គង់</th>
                                        <th>ស្ថានភាព</th>
                                        <th>សកម្មភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr class="item-row" 
                                        data-name="{{ strtolower($item->name) }}"
                                        data-status="{{ $item->needsRestock() ? 'low' : 'normal' }}"
                                        data-supplier="{{ $item->supplier ? $item->supplier->name : 'no-supplier' }}">
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $item->sku }}</code>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-secondary bg-opacity-10 rounded-2 me-2">
                                                    <i class="bi bi-box text-secondary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $item->name }}</div>
                                                    @if($item->description)
                                                        <small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-{{ $item->current_stock > $item->minimum_stock ? 'success' : 'warning' }} bg-opacity-10 text-{{ $item->current_stock > $item->minimum_stock ? 'success' : 'warning' }}">
                                                    {{ number_format($item->current_stock) }}
                                                </span>
                                                @if($item->current_stock <= $item->minimum_stock)
                                                    <i class="bi bi-exclamation-triangle text-warning" title="ស្តុកតិច"></i>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->minimum_stock) }}</td>
                                        <td>
                                            <span class="fw-semibold">${{ number_format($item->price, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($item->supplier)
                                                <span class="fw-medium">{{ $item->supplier->name }}</span>
                                            @else
                                                <span class="text-muted">
                                                    <i class="bi bi-exclamation-circle me-1"></i>គ្មានអ្នកផ្គត់ផ្គង់
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->needsRestock())
                                                <span class="badge bg-danger bg-opacity-10 text-danger">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>ស្តុកតិច
                                                </span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                    <i class="bi bi-check-circle me-1"></i>ស្តុកគ្រប់គ្រាន់
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Stock Update Button -->
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        data-bs-toggle="modal" data-bs-target="#stockModal{{ $item->id }}"
                                                        title="ធ្វើបច្ចុប្បន្នភាពស្តុក">
                                                    <i class="bi bi-arrow-up-circle"></i>
                                                </button>
                                                
                                                <!-- Edit Button -->
                                                <a href="{{ route('inventory.edit', $item) }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="កែសម្រួល">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                <!-- Delete Button -->
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}"
                                                        title="លុប">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Stock Update Modal -->
                                    <div class="modal fade" id="stockModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-arrow-up-circle me-2"></i>
                                                        ធ្វើបច្ចុប្បន្នភាពស្តុក: {{ $item->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('inventory.update', $item->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="alert alert-info">
                                                            <i class="bi bi-info-circle me-2"></i>
                                                            <strong>ស្តុកបច្ចុប្បន្ន:</strong> {{ number_format($item->current_stock) }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="quantity{{ $item->id }}" class="form-label fw-semibold">
                                                                បរិមាណ <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light">
                                                                    <i class="bi bi-123 text-muted"></i>
                                                                </span>
                                                                <input type="number" class="form-control" 
                                                                       id="quantity{{ $item->id }}" 
                                                                       name="quantity" min="1" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="action{{ $item->id }}" class="form-label fw-semibold">
                                                                សកម្មភាព <span class="text-danger">*</span>
                                                            </label>
                                                            <select class="form-select" id="action{{ $item->id }}" name="action" required>
                                                                <option value="add">បន្ថែមស្តុក</option>
                                                                <option value="remove">ដកស្តុក</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            បោះបង់
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="bi bi-check-circle me-1"></i>ធ្វើបច្ចុប្បន្នភាព
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">លុបទំនិញ</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-3">
                                                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                                    </div>
                                                    <p class="text-center">
                                                        តើអ្នកពិតជាចង់លុប <strong>{{ $item->name }}</strong> មែនទេ?
                                                    </p>
                                                    <div class="alert alert-warning">
                                                        <i class="bi bi-info-circle me-2"></i>
                                                        <small>សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ!</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        បោះបង់
                                                    </button>
                                                    <form method="POST" action="{{ route('inventory.destroy', $item) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-trash me-1"></i>លុប
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-box text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-muted mb-3">មិនទាន់មានទំនិញក្នុងស្តុកនៅឡើយទេ</h4>
                            <p class="text-muted mb-4">ចាប់ផ្តើមដោយការបន្ថែមទំនិញដំបូងរបស់អ្នក។</p>
                            <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-2"></i>បន្ថែមទំនិញដំបូង
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-light border d-flex align-items-center">
                <i class="bi bi-info-circle text-muted me-2"></i>
                <span class="small text-muted">
                    អ្នកប្រើ: <strong>whoisnut</strong> • 
                    កំពុងមើលនៅ: <strong>17/06/2025 10:30:31</strong> • 
                    សរុប: <strong>{{ $items->count() }} ទំនិញ</strong>
                </span>
            </div>
        </div>
    </div>
</div>

<style>
/* Card Hover Effects */
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    transition: all 0.2s ease-in-out;
}

/* Avatar Styling */
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Background Opacity */
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

/* Table Styling */
.table th {
    font-weight: 600;
    border-bottom: 2px solid #e9ecef;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Button Enhancements */
.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Modal Enhancements */
.modal-content {
    border: none;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

/* Badge styling */
.badge {
    font-weight: 500;
}

/* Code styling */
code {
    font-size: 0.85rem;
}

/* Khmer Font Support */
body {
    font-family: 'Noto Sans Khmer', 'Khmer OS', sans-serif;
}

/* Custom spacing for better Khmer text rendering */
.small {
    line-height: 1.6;
}

/* Text colors */
.text-gray-800 {
    color: #374151 !important;
}

/* Hide elements when filtering */
.item-row.hidden {
    display: none !important;
}

/* Collapsible animations */
.collapse {
    transition: height 0.35s ease;
}
</style>

<!-- JavaScript for Filtering and Export -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const searchInput = document.getElementById('searchItem');
    const statusFilter = document.getElementById('statusFilter');
    const supplierFilter = document.getElementById('supplierFilter');
    const itemCountSpan = document.getElementById('itemCount');
    
    function filterItems() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const supplierValue = supplierFilter.value;
        
        const items = document.querySelectorAll('.item-row');
        let visibleCount = 0;
        
        items.forEach(item => {
            let visible = true;
            
            // Search filter
            if (searchTerm && !item.getAttribute('data-name').includes(searchTerm)) {
                visible = false;
            }
            
            // Status filter
            if (statusValue && item.getAttribute('data-status') !== statusValue) {
                visible = false;
            }
            
            // Supplier filter
            if (supplierValue && item.getAttribute('data-supplier') !== supplierValue) {
                visible = false;
            }
            
            // Show/hide item
            if (visible) {
                item.classList.remove('hidden');
                visibleCount++;
            } else {
                item.classList.add('hidden');
            }
        });
        
        // Update visible count
        itemCountSpan.textContent = visibleCount;
    }
    
    // Add event listeners
    searchInput.addEventListener('input', filterItems);
    statusFilter.addEventListener('change', filterItems);
    supplierFilter.addEventListener('change', filterItems);
    
    // Clear filters function
    window.clearFilters = function() {
        searchInput.value = '';
        statusFilter.value = '';
        supplierFilter.value = '';
        filterItems();
    };
    
    // Export function
    window.exportData = function() {
        const table = document.querySelector('.table');
        const rows = Array.from(table.querySelectorAll('tr:not(.hidden)'));
        
        let csv = '';
        rows.forEach(row => {
            const cells = Array.from(row.querySelectorAll('th, td'));
            const rowData = cells.slice(0, -1).map(cell => {
                return '"' + cell.textContent.trim().replace(/"/g, '""') + '"';
            });
            csv += rowData.join(',') + '\n';
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'inventory_export_2025-06-17_10-30-31.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    };
    
    // Form validation for stock update modals
    const stockForms = document.querySelectorAll('form[action*="inventory.update"]');
    stockForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const quantityInput = form.querySelector('input[name="quantity"]');
            const quantity = parseInt(quantityInput.value);
            
            if (quantity <= 0) {
                e.preventDefault();
                alert('បរិមាណត្រូវតែធំជាង 0');
                return false;
            }
        });
    });
});
</script>

@endsection