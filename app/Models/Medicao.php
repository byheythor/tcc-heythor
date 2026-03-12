<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medicao extends Model
{
    protected $table = 'medicao';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'valor',
        'dado_biomedico_id',
        'data_hora',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
    ];

    public function dado_biomedico(): BelongsTo
    {
        return $this->belongsTo(DadoBiomedico::class, 'dado_biomedico_id');
    }
}
