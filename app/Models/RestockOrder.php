<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'inventory_item_id',
        'supplier_id',
        'quantity_ordered',
        'quantity_received',
        'status',
        'order_date',
        'expected_date',
        'received_date',
        'total_cost'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'order_date' => 'date',
        'expected_date' => 'date',
        'received_date' => 'date',
    ];

    /**
     * Get the inventory item for this order
     */
    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the supplier for this order
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}