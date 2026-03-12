<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Midia extends Model
{
    protected $table = 'midia';
    public $timestamps = false;

    protected $fillable = [
        'anexo',
        'tipo',
        'dado_biomedico_id',
        'categoria_midia_id',
    ];

    public function categoria_midia(): BelongsTo
    {
        return $this->belongsTo(CategoriaMidia::class, 'categoria_midia_id');
    }

    public function dado_biomedico(): BelongsTo
    {
        return $this->belongsTo(DadoBiomedico::class, 'dado_biomedico_id');
    }
}
