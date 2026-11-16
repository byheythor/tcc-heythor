<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medico extends Model
{
    protected $table = 'medico';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'crm',
        'contato',
        'especialidade_id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function especialidade(): BelongsTo
    {
        return $this->belongsTo(Especialidade::class, 'especialidade_id');
    }

    public function dadosBiomedicos(): HasMany
    {
        return $this->hasMany(DadoBiomedico::class, 'medico_id');
    }
}
