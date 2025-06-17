@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-arrow-repeat"></i> Restocking Management</h2>
            <a href="{{ route('restock.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New Order
            </a>
        </div>
    </div>
</div>

<!-- Low Stock Alerts -->
@if($lowStockItems->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle"></i> 
                    Items Needing Restock ({{ $lowStockItems->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($lowStockItems as $item)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">{{ $item->name }}</h6>
                                <p class="card-text">
                                    <small class="text-muted">SKU: {{ $item->sku }}</small><br>
                                    Current Stock: <span class="badge bg-danger">{{ $item->current_stock }}</span><br>
                                    Minimum Stock: {{ $item->minimum_stock }}<br>
                                    @if($item->supplier)
                                        Supplier: {{ $item->supplier->name }}
                                    @else
                                        <span class="text-warning">No supplier assigned</span>
                                    @endif
                                </p>
                                <a href="{{ route('restock.create') }}?item_id={{ $item->id }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Order Now
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
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Restock Orders</h5>
            </div>
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order Date</th>
                                    <th>Item</th>
                                    <th>Supplier</th>
                                    <th>Quantity</th>
                                    <th>Cost</th>
                                    <th>Status</th>
                                    <th>Expected</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->order_date->format('M d, Y') }}</td>
                                    <td>
                                        <strong>{{ $order->inventoryItem->name }}</strong>
                                        <br><small class="text-muted">{{ $order->inventoryItem->sku }}</small>
                                    </td>
                                    <td>{{ $order->supplier->name }}</td>
                                    <td>
                                        {{ $order->quantity_received }}/{{ $order->quantity_ordered }}
                                        @if($order->status == 'partial')
                                            <span class="badge bg-warning">Partial</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($order->total_cost, 2) }}</td>
                                    <td>
                                        @if($order->status == 'completed')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Completed
                                            </span>
                                        @elseif($order->status == 'partial')
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock"></i> Partial
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-hourglass"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->expected_date)
                                            {{ $order->expected_date->format('M d, Y') }}
                                            @if($order->expected_date->isPast() && $order->status != 'completed')
                                                <br><span class="text-danger small">Overdue</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->status != 'completed')
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    data-bs-toggle="modal" data-bs-target="#receiveModal{{ $order->id }}">
                                                <i class="bi bi-check-circle"></i> Receive
                                            </button>
                                        @else
                                            <span class="text-success">
                                                <i class="bi bi-check-circle"></i> Received
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
                                                <h5 class="modal-title">Receive Order: {{ $order->inventoryItem->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('restock.receive', $order) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Order Details:</label>
                                                        <ul class="list-unstyled">
                                                            <li><strong>Ordered:</strong> {{ $order->quantity_ordered }}</li>
                                                            <li><strong>Already Received:</strong> {{ $order->quantity_received }}</li>
                                                            <li><strong>Remaining:</strong> {{ $order->quantity_ordered - $order->quantity_received }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="quantity_received{{ $order->id }}" class="form-label">Quantity Received *</label>
                                                        <input type="number" class="form-control" 
                                                               id="quantity_received{{ $order->id }}" 
                                                               name="quantity_received" 
                                                               min="1" 
                                                               max="{{ $order->quantity_ordered - $order->quantity_received }}"
                                                               value="{{ $order->quantity_ordered - $order->quantity_received }}"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-check-circle"></i> Mark as Received
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
                    <div class="text-center py-4">
                        <i class="bi bi-arrow-repeat display-1 text-muted"></i>
                        <h4 class="text-muted mt-3">No restock orders found</h4>
                        <p class="text-muted">Create your first restock order to manage inventory.</p>
                        <a href="{{ route('restock.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create First Order
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection