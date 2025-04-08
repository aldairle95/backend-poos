<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->nullable()->default('text');
            $table->string('descripcion', 100)->nullable()->default('text');
            $table->string('codigo', 100)->nullable()->default('text');
            $table->integer('stock')->default(0);
            $table->decimal('preciocompra', 10, 2)->default(0.00);
            $table->decimal('precioventa', 10, 2)->default(0.00);
            $table->string('imagen')->nullable()->default('text');
            $table->unsignedBigInteger('id_categoria')->nullable(); 
            $table->timestamps();
             // Clave foránea
            $table->foreign('id_categoria')->references('id')->on('categorias')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
};
