<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisoesTable extends Migration
{
    public function up()
    {
        Schema::create('revisoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veiculo_id')->constrained('martins.veiculos')->onDelete('cascade');
            $table->date('data_prevista');
            $table->string('status')->nullable();
            $table->json('servicos')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('revisoes');
    }
}