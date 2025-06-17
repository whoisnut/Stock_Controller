@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-pencil"></i> Edit Inventory Item</h2>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Inventory
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit: {{ $item->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('inventory.update', $item) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Item Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $item->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU (Stock Keeping Unit) *</label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                       id="sku" name="sku" value="{{ old('sku', $item->sku) }}" required>
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="current_stock" class="form-label">Current Stock *</label>
                                <input type="number" class="form-control @error('current_stock') is-invalid @enderror" 
                                       id="current_stock" name="current_stock" value="{{ old('current_stock', $item->current_stock) }}" 
                                       min="0" required>
                                @error('current_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="minimum_stock" class="form-label">Minimum Stock *</label>
                                <input type="number" class="form-control @error('minimum_stock') is-invalid @enderror" 
                                       id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', $item->minimum_stock) }}" 
                                       min="0" required>
                                @error('minimum_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Unit Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $item->price) }}" 
                                           min="0" step="0.01" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                id="supplier_id" name="supplier_id">
                            <option value="">Select a supplier (optional)</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" 
                                        {{ old('supplier_id', $item->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('inventory.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Item Status</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Current Status:</strong>
                    @if($item->needsRestock())
                        <span class="badge bg-danger ms-2">
                            <i class="bi bi-exclamation-triangle"></i> Low Stock
                        </span>
                    @else
                        <span class="badge bg-success ms-2">
                            <i class="bi bi-check-circle"></i> In Stock
                        </span>
                    @endif
                </div>
                
                <div class="mb-3">
                    <strong>Created:</strong> {{ $item->created_at->format('M d, Y') }}
                </div>
                
                <div class="mb-3">
                    <strong>Last Updated:</strong> {{ $item->updated_at->format('M d, Y') }}
                </div>
                
                @if($item->restockOrders->count() > 0)
                    <div class="mb-3">
                        <strong>Recent Orders:</strong> {{ $item->restockOrders->count() }}
                        <br><small class="text-muted">Total orders placed for this item</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection