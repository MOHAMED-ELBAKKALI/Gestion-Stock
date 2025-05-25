<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_date',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_orders')
                    ->withTimestamps()
                    ->withPivot('quantity', 'price');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
