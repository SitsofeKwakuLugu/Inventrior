<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Inventory extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'company_id',
        'quantity',
        'unit_price',
        'average_cost',
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
