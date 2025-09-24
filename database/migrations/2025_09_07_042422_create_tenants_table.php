<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la tienda/tenant
            $table->string('slug')->unique(); // Identificador único (URL/subdominio)
            $table->string('email')->nullable(); // Email de contacto
            $table->string('logo')->nullable(); // Ruta del logo
            $table->string('color_primary')->default('#000000');   // Color 1
            $table->string('color_secondary')->default('#FFFFFF'); // Color 2
            $table->string('color_accent')->default('#CCCCCC');    // Color 3

            // Usuario responsable del tenant
            $table->foreignId('owner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
