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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo');
            $table->integer('id_cliente');
            $table->integer('id_vendedor'); 
            $table->string('productos');
            $table->decimal('impuesto');
            $table->decimal('neto');
            $table->decimal('total'); 
            $table->string('metodo_pago');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas');
    }
};
