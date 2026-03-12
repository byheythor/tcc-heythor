<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('midia', function (Blueprint $table) {
            $table->id();
            $table->string('anexo', 255);
            $table->string('tipo', 45)->nullable();
            $table->foreignId('dado_biomedico_id')->constrained('dado_biomedico');
            $table->foreignId('categoria_midia_id')->constrained('categoria_midia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('midia');
    }
};
