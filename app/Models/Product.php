<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
{
    use HasFactory;

    const CACHE_TTL = 60 * 6;
    const PER_PAGE = 10;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'created_by'
    ];
}
