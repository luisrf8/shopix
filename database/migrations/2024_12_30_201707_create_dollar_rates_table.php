<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDollarRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dollar_rates', function (Blueprint $table) {
            $table->id();
            $table->date('date');  // Fecha en la que se registró la tasa
            $table->decimal('rate', 10, 4);  // Tasa del dólar, con 4 decimales
            $table->timestamps();  // Para almacenar las fechas de creación y actualización
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dollar_rates');
    }
}
