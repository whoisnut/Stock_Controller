@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-people"></i> Supplier Management</h2>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Supplier
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Suppliers</h5>
            </div>
            <div class="card-body">
                @if($suppliers->count() > 0)
                    <div class="row">
                        @foreach($suppliers as $supplier)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ $supplier->name }}</h6>
                                        <span class="badge bg-primary">{{ $supplier->inventory_items_count }} items</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($supplier->contact_person)
                                        <p class="mb-2">
                                            <i class="bi bi-person"></i> 
                                            <strong>Contact:</strong> {{ $supplier->contact_person }}
                                        </p>
                                    @endif
                                    
                                    @if($supplier->email)
                                        <p class="mb-2">
                                            <i class="bi bi-envelope"></i> 
                                            <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                        </p>
                                    @endif
                                    
                                    @if($supplier->phone)
                                        <p class="mb-2">
                                            <i class="bi bi-telephone"></i> 
                                            <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a>
                                        </p>
                                    @endif
                                    
                                    @if($supplier->address)
                                        <p class="mb-2">
                                            <i class="bi bi-geo-alt"></i> 
                                            {{ Str::limit($supplier->address, 100) }}
                                        </p>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" class="d-inline" 
                                              onsubmit="return confirm('Are you sure? This will also remove supplier association from inventory items.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-people display-1 text-muted"></i>
                        <h4 class="text-muted mt-3">No suppliers found</h4>
                        <p class="text-muted">Start by adding your first supplier.</p>
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add First Supplier
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection