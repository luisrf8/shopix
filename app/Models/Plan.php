<?php
// app/Models/Plan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'duration_days', 'features'];

    public function payments()
    {
        return $this->hasMany(TenantPlanPayment::class);
    }
}
