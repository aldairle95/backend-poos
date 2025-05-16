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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->nullable()->default('text');
            $table->integer('documento')->unsigned()->nullable()->default(15);
            $table->string('email', 100)->nullable()->default('text');
            $table->string('telefono', 15)->nullable()->default('text');
            $table->string('direccion', 100)->nullable()->default('text');
            $table->date('fecha_nacimiento');
            $table->integer('compras');
            $table->timestamps('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
