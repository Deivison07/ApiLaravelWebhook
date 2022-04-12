<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantaoNoturnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plantao_noturnos', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_vendedor')->unsigned()->index()->default(0);
            $table->datetime('data_do_plantao');
            
        });

        Schema::table('plantao_noturnos', function (Blueprint $table) {
            $table->foreign('id_vendedor')->references('id')->on('vendedores');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('plantao_noturnos', function (Blueprint $table) {
            
            //remover chave estrangeira 
            $table->dropForeign('plantao_noturnos_id_vendedor_foreign');

            //remover a coluna unidade_id
            $table->dropColumn('id_vendedor');
        });

        Schema::dropIfExists('plantao_noturnos');

    }
}
