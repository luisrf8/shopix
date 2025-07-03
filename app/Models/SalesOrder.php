<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'address', 'status', 'preference', 'deliver_status'];

    public function details()
    {
        return $this->hasMany(SalesOrderDetail::class, 'sales_order_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'sales_order_id'); // Asegúrate de que la clave foránea sea la correcta
    }
    public function returns()
    {
        return $this->hasMany(SalesReturn::class);
    }
}
