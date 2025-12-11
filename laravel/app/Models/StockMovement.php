<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class StockMovement extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'product_id',
        'company_id',
        'type',
        'quantity',
        'note',
        'reference'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}