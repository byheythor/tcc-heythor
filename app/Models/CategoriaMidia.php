<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaMidia extends Model
{
    protected $table = 'categoria_midia';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function midias(): HasMany
    {
        return $this->hasMany(Midia::class, 'categoria_midia_id');
    }
}
