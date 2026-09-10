<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientProduct extends Model
{
    protected $fillable = [
        'client_id',
        'product_id',
        'description',
        'price',
        'purchase_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'purchase_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
