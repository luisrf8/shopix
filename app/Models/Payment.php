<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'sales_order_id', 'payment_method', 'amount', 'currency', 'reference', 'status'
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }
    public function images()
    {
        return $this->hasMany(PaymentImage::class);
    }
    public function payment()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'id');
    }
}
