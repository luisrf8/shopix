<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['provider_id', 'date'];

    public function detalles()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }
}
