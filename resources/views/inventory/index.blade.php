@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-box"></i> Inventory Management</h2>
            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Item
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Inventory Items</h5>
            </div>
            <div class="card-body">
                @if($items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>Current Stock</th>
                                    <th>Min Stock</th>
                                    <th>Price</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td><code>{{ $item->sku }}</code></td>
                                    <td>
                                        <strong>{{ $item->name }}</strong>
                                        @if($item->description)
                                            <br><small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->current_stock > $item->minimum_stock ? 'success' : 'warning' }}">
                                            {{ $item->current_stock }}
                                        </span>
                                    </td>
                                    <td>{{ $item->minimum_stock }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->supplier ? $item->supplier->name : 'No supplier' }}</td>
                                    <td>
                                        @if($item->needsRestock())
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle"></i> Low Stock
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> In Stock
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <!-- Stock Update Buttons -->
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#stockModal{{ $item->id }}">
                                                <i class="bi bi-arrow-up-circle"></i>
                                            </button>
                                            
                                            <!-- Edit Button -->
                                            <a href="{{ route('inventory.edit', $item) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            
                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('inventory.destroy', $item) }}" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this item?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Stock Update Modal -->
                                <div class="modal fade" id="stockModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Stock: {{ $item->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('inventory.update', $item->id) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Current Stock: {{ $item->current_stock }}</label>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="quantity{{ $item->id }}" class="form-label">Quantity</label>
                                                        <input type="number" class="form-control" id="quantity{{ $item->id }}" 
                                                               name="quantity" min="1" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="action{{ $item->id }}" class="form-label">Action</label>
                                                        <select class="form-select" id="action{{ $item->id }}" name="action" required>
                                                            <option value="add">Add Stock</option>
                                                            <option value="remove">Remove Stock</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Stock</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-box display-1 text-muted"></i>
                        <h4 class="text-muted mt-3">No inventory items found</h4>
                        <p class="text-muted">Start by adding your first inventory item.</p>
                        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add First Item
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection