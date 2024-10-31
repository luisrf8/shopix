<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUserIdFromProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Elimina la clave foránea
            $table->dropColumn('user_id'); // Elimina el campo user_id
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Reagrega el campo user_id
        });
    }
}
