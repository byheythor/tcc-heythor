<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaMidia extends Model
{
    protected $table = 'categoria_midia';
    public $timestamps = false;

    protected $fillable = [
        'nome',
    ];

    public function midias(): HasMany
    {
        return $this->hasMany(Midia::class, 'categoria_midia_id');
    }
}
