<?php

namespace App\Http\Controllers;

use App\Models\RestockOrder;
use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Http\Request;

class RestockController extends Controller
{
    /**
     * Display a listing of restock orders
     */
    public function index()
    {
        $orders = RestockOrder::with(['inventoryItem', 'supplier'])->latest()->get();
        $lowStockItems = InventoryItem::needingRestock();
        
        return view('restock.index', compact('orders', 'lowStockItems'));
    }

    /**
     * Show the form for creating a new restock order
     */
    public function create()
    {
        $items = InventoryItem::with('supplier')->get();
        $suppliers = Supplier::all();
        return view('restock.create', compact('items', 'suppliers'));
    }

    /**
     * Store a newly created restock order
     */
    public function store(Request $request)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity_ordered' => 'required|integer|min:1',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date|after:order_date',
            'total_cost' => 'required|numeric|min:0'
        ]);

        RestockOrder::create($request->all());

        return redirect()->route('restock.index')
            ->with('success', 'Restock order created successfully.');
    }

    /**
     * Mark order as received and update inventory
     */
    public function receive(Request $request, RestockOrder $order)
    {
        $request->validate([
            'quantity_received' => 'required|integer|min:1|max:' . $order->quantity_ordered
        ]);

        // Update the order
        $order->quantity_received = $request->quantity_received;
        $order->received_date = now();
        
        // Update status based on quantity received
        if ($request->quantity_received == $order->quantity_ordered) {
            $order->status = 'completed';
        } else {
            $order->status = 'partial';
        }
        
        $order->save();

        // Update inventory stock
        $order->inventoryItem->current_stock += $request->quantity_received;
        $order->inventoryItem->save();

        return redirect()->route('restock.index')
            ->with('success', 'Order marked as received and inventory updated.');
    }
}