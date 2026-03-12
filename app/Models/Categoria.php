<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $table = 'categoria';
    public $timestamps = false;

    protected $fillable = [
        'nome',
    ];

    public function dadosBiomedicos(): HasMany
    {
        return $this->hasMany(DadoBiomedico::class, 'categoria_id');
    }
}
