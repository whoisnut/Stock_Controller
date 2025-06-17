<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\RestockOrder;
use App\Models\Supplier;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with key metrics and alerts
     */
    public function index()
    {
        // Get items that need restocking (low stock alerts)
        $lowStockItems = InventoryItem::needingRestock();
        
        // Get recent restock orders
        $recentOrders = RestockOrder::with(['inventoryItem', 'supplier'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get summary statistics
        $totalItems = InventoryItem::count();
        $totalSuppliers = Supplier::count();
        $pendingOrders = RestockOrder::where('status', 'pending')->count();
        
        return view('dashboard', compact(
            'lowStockItems',
            'recentOrders',
            'totalItems',
            'totalSuppliers',
            'pendingOrders'
        ));
    }
}