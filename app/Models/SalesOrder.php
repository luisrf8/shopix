<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'address', 'status', 'preference'];

    public function details()
    {
        return $this->hasMany(SalesOrderDetail::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
