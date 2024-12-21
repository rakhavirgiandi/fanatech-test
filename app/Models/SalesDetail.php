<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesDetail extends Model
{
    //

    protected $table = 'sales_details';
    protected $appends = ['price'];

    protected $fillable = [
        'qty'
    ];
    
    public function sales(): BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sales_id', 'id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }

    protected function price(): Attribute {
        return new Attribute(
            get: function (mixed $value, array $attributes)  {
                if ($this->relationLoaded('inventory') && $this->inventory) {
                    return $this->inventory->price * $this->qty;
                }
            }
        );
    }
}
