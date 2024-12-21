<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    //
    protected $table = 'inventories';

    protected $fillable = [
        'code',
        'name',
        'price',
        'stock',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(SalesDetail::class, 'inventory_id', 'id');
    }

    public function purcases(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class, 'inventory_id', 'id');
    }
}
