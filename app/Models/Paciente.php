<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Paciente
 * 
 * @property int $id
 * @property Carbon $data_nascimento
 * @property string $nome
 * @property string $email
 * @property string|null $cpf
 * @property string $senha
 * @property string|null $celular
 * 
 * @property Collection|Categorium[] $categoria
 * @property Collection|CategoriaMidium[] $categoria_midia
 * @property Collection|DadoBiomedico[] $dado_biomedicos
 * @property Collection|Especialidade[] $especialidades
 * @property Collection|Medicao[] $medicaos
 * @property Collection|Medico[] $medicos
 * @property Collection|Midium[] $midia
 *
 * @package App\Models
 */
class Paciente extends Model
{
	protected $table = 'paciente';
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'data_nascimento' => 'datetime'
	];

	protected $fillable = [
		'data_nascimento',
		'nome',
		'email',
		'cpf',
		'senha',
		'celular'
	];

	public function categoria()
	{
		return $this->hasMany(Categorium::class);
	}

	public function categoria_midia()
	{
		return $this->hasMany(CategoriaMidium::class);
	}

	public function dado_biomedicos()
	{
		return $this->hasMany(DadoBiomedico::class);
	}

	public function especialidades()
	{
		return $this->hasMany(Especialidade::class);
	}

	public function medicaos()
	{
		return $this->hasMany(Medicao::class);
	}

	public function medicos()
	{
		return $this->hasMany(Medico::class);
	}

	public function midia()
	{
		return $this->hasMany(Midium::class);
	}
}
