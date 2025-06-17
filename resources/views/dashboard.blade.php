@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-speedometer2 me-2 text-primary"></i>
                ផ្ទាំងគ្រប់គ្រង
            </h1>
            <p class="text-muted mb-0">សូមស្វាគមន៍មកវិញ! នេះជាអ្វីដែលកំពុងកើតឡើងជាមួយស្តុកទំនិញរបស់អ្នក។</p>
        </div>
        <div class="text-muted">
            <small>ធ្វើបច្ចុប្បន្នភាពចុងក្រោយ: {{ now()->format('d/m/Y H:i') }}</small>
        </div>
    </div>

    <!-- Summary Cards with Improved Design -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="h4 mb-1 text-dark fw-bold">{{ number_format($totalItems) }}</h2>
                            <p class="text-muted mb-0 small">ទំនិញសរុប</p>
                            <div class="progress mt-2" style="height: 3px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-box text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="h4 mb-1 text-dark fw-bold">{{ number_format($totalSuppliers) }}</h2>
                            <p class="text-muted mb-0 small">អ្នកផ្គត់ផ្គង់សកម្ម</p>
                            <div class="progress mt-2" style="height: 3px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-people text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="h4 mb-1 text-dark fw-bold">{{ number_format($lowStockItems->count()) }}</h2>
                            <p class="text-muted mb-0 small">ការជូនដំណឹងស្តុកតិច</p>
                            <div class="progress mt-2" style="height: 3px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="h4 mb-1 text-dark fw-bold">{{ number_format($pendingOrders) }}</h2>
                            <p class="text-muted mb-0 small">ការបញ្ជាទិញកំពុងរង់ចាំ</p>
                            <div class="progress mt-2" style="height: 3px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 45%"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-clock text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Low Stock Alerts -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                            ការជូនដំណឹងស្តុកទំនិញតិច
                        </h5>
                        @if($lowStockItems->count() > 0)
                            <span class="badge bg-warning bg-opacity-10 text-warning">{{ $lowStockItems->count() }} ប្រភេទ</span>
                        @endif
                    </div>
                </div>
                <div class="card-body pt-3">
                    @if($lowStockItems->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($lowStockItems->take(5) as $item)
                                <div class="list-group-item border-0 px-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-2 me-3">
                                            <i class="bi bi-box text-warning"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $item->name }}</h6>
                                            <div class="row g-0 small text-muted">
                                                <div class="col-6">
                                                    <i class="bi bi-graph-down me-1"></i>
                                                    បច្ចុប្បន្ន៖ <span class="fw-medium">{{ number_format($item->current_stock) }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <i class="bi bi-flag me-1"></i>
                                                    អប្បបរមា៖ <span class="fw-medium">{{ number_format($item->minimum_stock) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                {{ round(($item->current_stock / $item->minimum_stock) * 100) }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($lowStockItems->count() > 5)
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    មើលទំនិញស្តុកតិចទាំងអស់ ({{ $lowStockItems->count() - 5 }} ទៀត)
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="avatar-lg bg-success bg-opacity-10 rounded-3 mx-auto mb-3">
                                <i class="bi bi-check-circle text-success fs-2"></i>
                            </div>
                            <h6 class="text-muted">ទំនិញទាំងអស់មានស្តុកគ្រប់គ្រាន់!</h6>
                            <p class="text-muted small mb-0">គ្មានទំនិញណាមួយដែលនៅក្រោមកម្រិតស្តុកអប្បបរមានោះទេ។</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history text-info me-2"></i>
                            ការបញ្ជាទិញថ្មីៗ
                        </h5>
                        @if($recentOrders->count() > 0)
                            <a href="#" class="text-decoration-none small">មើលការបញ្ជាទិញទាំងអស់</a>
                        @endif
                    </div>
                </div>
                <div class="card-body pt-3">
                    @if($recentOrders->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentOrders->take(5) as $order)
                                <div class="list-group-item border-0 px-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'partial' ? 'warning' : 'secondary') }} bg-opacity-10 rounded-2 me-3">
                                            <i class="bi bi-{{ $order->status == 'completed' ? 'check-circle' : ($order->status == 'partial' ? 'clock' : 'hourglass') }} text-{{ $order->status == 'completed' ? 'success' : ($order->status == 'partial' ? 'warning' : 'secondary') }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $order->inventoryItem->name }}</h6>
                                            <div class="small text-muted">
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        <i class="bi bi-building me-1"></i>
                                                        {{ $order->supplier->name }}
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-box me-1"></i>
                                                        បរិមាណ៖ {{ number_format($order->quantity_ordered) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'partial' ? 'warning' : 'secondary') }} bg-opacity-10 text-{{ $order->status == 'completed' ? 'success' : ($order->status == 'partial' ? 'warning' : 'secondary') }}">
                                                @if($order->status == 'completed')
                                                    បានបញ្ចប់
                                                @elseif($order->status == 'partial')
                                                    មិនពេញលេញ
                                                @elseif($order->status == 'pending')
                                                    កំពុងរង់ចាំ
                                                @else
                                                    {{ ucfirst($order->status) }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($recentOrders->count() > 5)
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    មើលការបញ្ជាទិញទាំងអស់ ({{ $recentOrders->count() - 5 }} ទៀត)
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="avatar-lg bg-info bg-opacity-10 rounded-3 mx-auto mb-3">
                                <i class="bi bi-inbox text-info fs-2"></i>
                            </div>
                            <h6 class="text-muted">គ្មានការបញ្ជាទិញថ្មីៗ</h6>
                            <p class="text-muted small mb-3">អ្នកមិនទាន់បានធ្វើការបញ្ជាទិញណាមួយថ្មីៗនេះទេ។</p>
                            <a href="#" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>
                                បង្កើតការបញ្ជាទិញថ្មី
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-lightning text-primary me-2"></i>
                        សកម្មភាពរហ័ស
                    </h5>
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="#" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none">
                                <i class="bi bi-plus-circle fs-3 mb-2"></i>
                                <span>បន្ថែមទំនិញថ្មី</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="#" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none">
                                <i class="bi bi-cart-plus fs-3 mb-2"></i>
                                <span>បង្កើតការបញ្ជាទិញ</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="#" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none">
                                <i class="bi bi-file-earmark-text fs-3 mb-2"></i>
                                <span>បង្កើតរបាយការណ៍</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="#" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none">
                                <i class="bi bi-gear fs-3 mb-2"></i>
                                <span>ការកំណត់</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics Section -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up text-success me-2"></i>
                        ស្ថិតិស្តុកទំនិញ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="border-start border-primary border-3 ps-3">
                                <h4 class="mb-1 text-primary">85%</h4>
                                <p class="text-muted mb-0 small">ការបំពេញស្តុក</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-start border-success border-3 ps-3">
                                <h4 class="mb-1 text-success">92%</h4>
                                <p class="text-muted mb-0 small">ភាពត្រឹមត្រូវស្តុក</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-start border-info border-3 ps-3">
                                <h4 class="mb-1 text-info">7 ថ្ងៃ</h4>
                                <p class="text-muted mb-0 small">ពេលវេលាបញ្ជាទិញជាមធ្យម</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-check text-warning me-2"></i>
                        ការងារថ្ងៃនេះ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-check2-square text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">ពិនិត្យស្តុកទំនិញ</h6>
                            <small class="text-muted">គ្រាន់តែបញ្ចប់</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-clock text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">អនុម័តការបញ្ជាទិញ</h6>
                            <small class="text-muted">កំពុងរង់ចាំ</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-info bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-file-text text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">រៀបចំរបាយការណ៍</h6>
                            <small class="text-muted">ត្រូវការបញ្ចប់</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-hover {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.icon-shape {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-sm {
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-lg {
    width: 4rem;
    height: 4rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

.text-gray-800 {
    color: #374151 !important;
}

/* Khmer Font Support */
body {
    font-family: 'Khmer OS', 'Moul', sans-serif;
}

.card-title,
.h1, .h2, .h3, .h4, .h5, .h6,
h1, h2, h3, h4, h5, h6 {
    font-family: 'Khmer OS Siemreap', 'Khmer OS', sans-serif;
}

/* RTL Support for better Khmer text rendering */
.khmer-text {
    direction: ltr;
    text-align: left;
}

/* Custom spacing for Khmer text */
.small {
    line-height: 1.6;
}

p, .text-muted {
    line-height: 1.7;
}
</style>

<!-- Optional: Add Khmer fonts if not already included -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&display=swap" rel="stylesheet">

@endsection