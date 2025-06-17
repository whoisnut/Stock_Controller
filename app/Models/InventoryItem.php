<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'sku',
        'current_stock',
        'minimum_stock',
        'price',
        'supplier_id'
    ];

    /**
     * Get the supplier for this inventory item
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get restock orders for this item
     */
    public function restockOrders()
    {
        return $this->hasMany(RestockOrder::class);
    }

    /**
     * Check if item needs restocking (current stock below minimum)
     */
    public function needsRestock()
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    /**
     * Get items that need restocking
     */
    public static function needingRestock()
    {
        return static::whereColumn('current_stock', '<=', 'minimum_stock')->get();
    }
}