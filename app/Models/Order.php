<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
    ];

    public function scopeSelf(Builder $query)
    {
        $query->where('user_id', auth()->id());
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
