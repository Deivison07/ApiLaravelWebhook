<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunIdVendedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('webhooks', function (Blueprint $table) {
            
            $table->bigInteger('id_vendedor')->unsigned()->index()->default(0);
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
        Schema::table('webhook', function (Blueprint $table) {
            $table->dropColumn(['id_vendedor']);
        });

    }
}
