<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPriceColumnInPurchaseOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_detail', function (Blueprint $table) {
            $table->integer('price')->nullable()->change(); // Permitir NULL en la columna 'price'
        });
    }
    
    public function down()
    {
        Schema::table('purchase_order_detail', function (Blueprint $table) {
            $table->integer('price')->nullable(false)->change(); // Hacer la columna no nullable si se hace rollback
        });
    }
    
}
