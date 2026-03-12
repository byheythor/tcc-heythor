<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicao', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('valor', 45);
            $table->foreignId('dado_biomedico_id')->constrained('dado_biomedico');
            $table->dateTime('data_hora');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicao');
    }
};
