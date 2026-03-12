<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DadoBiomedico extends Model
{
    protected $table = 'dado_biomedico';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'medico_id',
        'paciente_id',
        'categoria_id',
        'valor_referencia',
        'unidade_medida',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }

    public function medicoes(): HasMany
    {
        return $this->hasMany(Medicao::class, 'dado_biomedico_id');
    }

    public function midias(): HasMany
    {
        return $this->hasMany(Midia::class, 'dado_biomedico_id');
    }
}
