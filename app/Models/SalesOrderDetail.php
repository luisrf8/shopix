<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['sales_order_id', 'product_variant_id', 'quantity', 'price', 'amount'];

    // En el modelo SalesOrderDetail
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

}
