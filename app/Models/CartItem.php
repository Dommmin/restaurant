<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $guarded = [];

    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }
}
