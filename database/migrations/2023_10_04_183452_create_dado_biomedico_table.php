<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dado_biomedico', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->foreignId('medico_id')->nullable()->constrained('medico');
            $table->foreignId('paciente_id')->constrained('users');
            $table->foreignId('categoria_id')->nullable()->constrained('categoria');
            $table->string('valor_referencia', 30);
            $table->string('unidade_medida', 30);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dado_biomedico');
    }
};
