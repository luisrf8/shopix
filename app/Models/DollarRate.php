<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DollarRate extends Model
{
    use HasFactory;

    // Define la tabla asociada al modelo (opcional si el nombre de la tabla sigue la convención)
    protected $table = 'dollar_rates';

    // Campos que pueden ser asignados masivamente (mass assignment)
    protected $fillable = ['date', 'rate'];

    // Si quieres trabajar con la fecha automáticamente, Laravel maneja esto a través de 'created_at' y 'updated_at'
    // Si no deseas que Laravel maneje estas columnas, puedes desactivar la opción.
    public $timestamps = true;  // Este valor es verdadero por defecto, puedes omitirlo si no quieres cambiarlo.

    // Opcional: Puedes agregar una mutator si quieres manipular el valor antes de guardarlo o al recuperarlo
    // Ejemplo de cómo formatear la tasa antes de guardarla (si es necesario)
    public function setRateAttribute($value)
    {
        $this->attributes['rate'] = round($value, 4);  // Asegurarte que la tasa tiene 4 decimales
    }
}
