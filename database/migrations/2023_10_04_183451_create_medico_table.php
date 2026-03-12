<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medico', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('crm', 20)->nullable();
            $table->string('contato', 45)->nullable();
            $table->foreignId('especialidade_id')->constrained('especialidade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medico');
    }
};
