<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'nome',
        'email',
        'cpf',
        'celular',
        'data_nascimento',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'data_nascimento' => 'date',
    ];

    public function getNameAttribute(): string
    {
        return (string) $this->nome;
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['nome'] = $value;
    }

    public function dadosBiomedicos(): HasMany
    {
        return $this->hasMany(DadoBiomedico::class, 'paciente_id');
    }

    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class);
    }

    public function especialidades(): HasMany
    {
        return $this->hasMany(Especialidade::class);
    }

    public function medicos(): HasMany
    {
        return $this->hasMany(Medico::class);
    }

    public function categoriasMidia(): HasMany
    {
        return $this->hasMany(CategoriaMidia::class);
    }
}
