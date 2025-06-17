@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-arrow-repeat me-2 text-primary"></i>
                គ្រប់គ្រងការបញ្ជាទិញស្តុក
            </h1>
            <p class="text-muted mb-0">គ្រប់គ្រងការបញ្ជាទិញទំនិញ និងតាមដានស្ថានភាពដឹកជញ្ជូន</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filterSection">
                <i class="bi bi-funnel me-1"></i> តម្រង
            </button>
            <a href="{{ route('restock.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> បង្កើតការបញ្ជាទិញថ្មី
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
                            <i class="bi bi-cart text-primary"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $orders->count() }}</h4>
                            <small class="text-muted">ការបញ្ជាទិញសរុប</small>
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
                            <h4 class="mb-0">{{ $lowStockItems->count() }}</h4>
                            <small class="text-muted">ទំនិញស្តុកតិច</small>
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
                            <h4 class="mb-0">{{ $orders->where('status', 'completed')->count() }}</h4>
                            <small class="text-muted">បានបញ្ចប់</small>
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
                            <i class="bi bi-clock text-info"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $orders->whereIn('status', ['pending', 'partial'])->count() }}</h4>
                            <small class="text-muted">កំពុងដំណើរការ</small>
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
                        <label class="form-label small fw-semibold">ស្វែងរកតាមទំនិញ</label>
                        <input type="text" class="form-control" placeholder="បញ្ចូលឈ្មោះទំនិញ..." id="searchItem">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">តម្រងតាមស្ថានភាព</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">ទាំងអស់</option>
                            <option value="pending">កំពុងរង់ចាំ</option>
                            <option value="partial">មិនពេញលេញ</option>
                            <option value="completed">បានបញ្ចប់</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">តម្រងតាមអ្នកផ្គត់ផ្គង់</label>
                        <select class="form-select" id="supplierFilter">
                            <option value="">ទាំងអស់</option>
                            @foreach($orders->unique('supplier.name')->pluck('supplier')->filter() as $supplier)
                                <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                            @endforeach
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

    <!-- Low Stock Alerts -->
    @if($lowStockItems->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm border-start border-warning border-4">
                <div class="card-header bg-warning bg-opacity-10 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 text-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            ទំនិញត្រូវការបញ្ជាទិញបន្ថែម ({{ $lowStockItems->count() }})
                        </h5>
                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="collapse" data-bs-target="#lowStockSection">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body collapse show" id="lowStockSection">
                    <div class="row g-3">
                        @foreach($lowStockItems as $item)
                        <div class="col-md-6 col-xl-4">
                            <div class="card border-warning border-opacity-25 h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $item->name }}</h6>
                                        <span class="badge bg-danger">{{ $item->current_stock }}</span>
                                    </div>
                                    <div class="small text-muted mb-3">
                                        <div><strong>SKU:</strong> {{ $item->sku }}</div>
                                        <div><strong>ស្តុកបច្ចុប្បន្ន:</strong> {{ $item->current_stock }}</div>
                                        <div><strong>ស្តុកអប្បបរមា:</strong> {{ $item->minimum_stock }}</div>
                                        @if($item->supplier)
                                            <div><strong>អ្នកផ្គត់ផ្គង់:</strong> {{ $item->supplier->name }}</div>
                                        @else
                                            <div class="text-warning"><i class="bi bi-exclamation-circle me-1"></i>មិនទាន់កំណត់អ្នកផ្គត់ផ្គង់</div>
                                        @endif
                                    </div>
                                    <a href="{{ route('restock.create') }}?item_id={{ $item->id }}" 
                                       class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-plus-circle me-1"></i> បញ្ជាទិញឥឡូវ
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Orders List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-ul text-secondary me-2"></i>
                            បញ្ជីការបញ្ជាទិញស្តុក
                        </h5>
                        <small class="text-muted">ការបង្ហាញ: {{ $orders->count() }} ការបញ្ជាទិញ</small>
                    </div>
                </div>
                <div class="card-body pt-3">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>កាលបរិច្ឆេទបញ្ជាទិញ</th>
                                        <th>ទំនិញ</th>
                                        <th>អ្នកផ្គត់ផ្គង់</th>
                                        <th>បរិមាណ</th>
                                        <th>តម្លៃ</th>
                                        <th>ស្ថានភាព</th>
                                        <th>កាលបរិច្ឆេទរំពឹងទុក</th>
                                        <th>សកម្មភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr class="order-row" 
                                        data-item="{{ strtolower($order->inventoryItem->name) }}"
                                        data-status="{{ $order->status }}"
                                        data-supplier="{{ $order->supplier->name }}">
                                        <td>
                                            <div class="fw-semibold">{{ $order->order_date->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $order->order_date->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-secondary bg-opacity-10 rounded-2 me-2">
                                                    <i class="bi bi-box text-secondary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $order->inventoryItem->name }}</div>
                                                    <small class="text-muted">{{ $order->inventoryItem->sku }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $order->supplier->name }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-medium">{{ $order->quantity_received }}/{{ $order->quantity_ordered }}</span>
                                                @if($order->status == 'partial')
                                                    <span class="badge bg-warning bg-opacity-10 text-warning small">មិនពេញលេញ</span>
                                                @endif
                                            </div>
                                            <div class="progress mt-1" style="height: 3px;">
                                                <div class="progress-bar bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'partial' ? 'warning' : 'secondary') }}" 
                                                     style="width: {{ ($order->quantity_received / $order->quantity_ordered) * 100 }}%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">${{ number_format($order->total_cost, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($order->status == 'completed')
                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                    <i class="bi bi-check-circle me-1"></i> បានបញ្ចប់
                                                </span>
                                            @elseif($order->status == 'partial')
                                                <span class="badge bg-warning bg-opacity-10 text-warning">
                                                    <i class="bi bi-clock me-1"></i> មិនពេញលេញ
                                                </span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                    <i class="bi bi-hourglass me-1"></i> កំពុងរង់ចាំ
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->expected_date)
                                                <div class="fw-medium">{{ $order->expected_date->format('d/m/Y') }}</div>
                                                @if($order->expected_date->isPast() && $order->status != 'completed')
                                                    <small class="text-danger">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>យឺតពេល
                                                    </small>
                                                @endif
                                            @else
                                                <span class="text-muted small">មិនទាន់កំណត់</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->status != 'completed')
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        data-bs-toggle="modal" data-bs-target="#receiveModal{{ $order->id }}">
                                                    <i class="bi bi-check-circle me-1"></i> ទទួល
                                                </button>
                                            @else
                                                <span class="text-success">
                                                    <i class="bi bi-check-circle me-1"></i> បានទទួល
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Receive Modal -->
                                    @if($order->status != 'completed')
                                    <div class="modal fade" id="receiveModal{{ $order->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-box-arrow-in-down me-2"></i>
                                                        ទទួលការបញ្ជាទិញ: {{ $order->inventoryItem->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('restock.receive', $order) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="alert alert-info">
                                                            <h6 class="alert-heading">
                                                                <i class="bi bi-info-circle me-2"></i>
                                                                ព័ត៌មានការបញ្ជាទិញ:
                                                            </h6>
                                                            <ul class="list-unstyled mb-0">
                                                                <li><strong>បានបញ្ជាទិញ:</strong> {{ number_format($order->quantity_ordered) }}</li>
                                                                <li><strong>បានទទួលរួច:</strong> {{ number_format($order->quantity_received) }}</li>
                                                                <li><strong>នៅសល់:</strong> {{ number_format($order->quantity_ordered - $order->quantity_received) }}</li>
                                                            </ul>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="quantity_received{{ $order->id }}" class="form-label fw-semibold">
                                                                បរិមាណដែលទទួលបាន <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light">
                                                                    <i class="bi bi-box text-muted"></i>
                                                                </span>
                                                                <input type="number" class="form-control" 
                                                                       id="quantity_received{{ $order->id }}" 
                                                                       name="quantity_received" 
                                                                       min="1" 
                                                                       max="{{ $order->quantity_ordered - $order->quantity_received }}"
                                                                       value="{{ $order->quantity_ordered - $order->quantity_received }}"
                                                                       required>
                                                            </div>
                                                            <div class="form-text">
                                                                អតិបរមា: {{ number_format($order->quantity_ordered - $order->quantity_received) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            បោះបង់
                                                        </button>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="bi bi-check-circle me-1"></i> បញ្ជាក់ការទទួល
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-arrow-repeat text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-muted mb-3">មិនទាន់មានការបញ្ជាទិញស្តុកនៅឡើយទេ</h4>
                            <p class="text-muted mb-4">បង្កើតការបញ្ជាទិញដំបូងរបស់អ្នកដើម្បីគ្រប់គ្រងស្តុកទំនិញ។</p>
                            <a href="{{ route('restock.create') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-2"></i> បង្កើតការបញ្ជាទិញដំបូង
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
                    កំពុងមើលនៅ: <strong>17/06/2025 10:21</strong> • 
                    សរុប: <strong>{{ $orders->count() }} ការបញ្ជាទិញ</strong>
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

.bg-opacity-25 {
    --bs-bg-opacity: 0.25;
}

/* Table Styling */
.table th {
    font-weight: 600;
    border-bottom: 2px solid #e9ecef;
    background-color: #f8f9fa;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Progress bar styling */
.progress {
    background-color: rgba(0, 0, 0, 0.1);
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

/* Border styling */
.border-4 {
    border-width: 4px !important;
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
.order-row.hidden {
    display: none !important;
}

/* Collapsible animations */
.collapse {
    transition: height 0.35s ease;
}
</style>

<!-- JavaScript for Filtering -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const searchInput = document.getElementById('searchItem');
    const statusFilter = document.getElementById('statusFilter');
    const supplierFilter = document.getElementById('supplierFilter');
    
    function filterOrders() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const supplierValue = supplierFilter.value;
        
        const orders = document.querySelectorAll('.order-row');
        let visibleCount = 0;
        
        orders.forEach(order => {
            let visible = true;
            
            // Search filter
            if (searchTerm && !order.getAttribute('data-item').includes(searchTerm)) {
                visible = false;
            }
            
            // Status filter
            if (statusValue && order.getAttribute('data-status') !== statusValue) {
                visible = false;
            }
            
            // Supplier filter
            if (supplierValue && order.getAttribute('data-supplier') !== supplierValue) {
                visible = false;
            }
            
            // Show/hide order
            if (visible) {
                order.classList.remove('hidden');
                visibleCount++;
            } else {
                order.classList.add('hidden');
            }
        });
        
        // Update visible count
        document.querySelector('.card-header small').textContent = `ការបង្ហាញ: ${visibleCount} ការបញ្ជាទិញ`;
    }
    
    // Add event listeners
    searchInput.addEventListener('input', filterOrders);
    statusFilter.addEventListener('change', filterOrders);
    supplierFilter.addEventListener('change', filterOrders);
    
    // Clear filters function
    window.clearFilters = function() {
        searchInput.value = '';
        statusFilter.value = '';
        supplierFilter.value = '';
        filterOrders();
    };
    
    // Form validation for receive modals
    const receiveForms = document.querySelectorAll('form[action*="receive"]');
    receiveForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const quantityInput = form.querySelector('input[name="quantity_received"]');
            const quantity = parseInt(quantityInput.value);
            const max = parseInt(quantityInput.getAttribute('max'));
            
            if (quantity > max) {
                e.preventDefault();
                alert('បរិមាណដែលបញ្ចូលលើសពីចំនួនដែលអាចទទួលបាន');
                return false;
            }
        });
    });
});
</script>

@endsection