<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarefasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->integer('status');//0 ou 1
            $table->string('description',250);
            $table->boolean('level');
            $table->timestamps();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tarefas',function (Blueprint $table) {
            $table->dropForeign('tarefas_user_id_foreign');
            $tabble->dropColumn('unidade_id');
        });
        Schema::dropIfExists('tarefas');
    }
}
