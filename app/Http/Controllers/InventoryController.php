<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of inventory items
     */
    public function index()
    {
        $items = InventoryItem::with('supplier')->get();
        return view('inventory.index', compact('items'));
    }

    /**
     * Show the form for creating a new inventory item
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('inventory.create', compact('suppliers'));
    }

    /**
     * Store a newly created inventory item
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:inventory_items',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id'
        ]);

        InventoryItem::create($request->all());

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Show the form for editing an inventory item
     */
    public function edit($id)
{
    $item = InventoryItem::findOrFail($id);
    $suppliers = Supplier::all();
    return view('inventory.edit', compact('item', 'suppliers'));
}

    /**
     * Update the specified inventory item
     */
    public function update(Request $request, InventoryItem $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:inventory_items,sku,' . $item->id,
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id'
        ]);

        $item->update($request->all());

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item
     */
    public function destroy(InventoryItem $item)
    {
        $item->delete();
        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Update stock quantity (add or remove stock)
     */
    public function updateStock(Request $request, InventoryItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'action' => 'required|in:add,remove'
        ]);

        if ($request->action === 'add') {
            $item->current_stock += $request->quantity;
        } else {
            $item->current_stock = max(0, $item->current_stock - $request->quantity);
        }

        $item->save();

        return redirect()->route('inventory.index')
            ->with('success', 'Stock updated successfully.');
    }
}