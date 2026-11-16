<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES = ['categoria', 'especialidade', 'medico', 'categoria_midia'];

    public function up(): void
    {
        $records = [];
        $owners = [];
        foreach (self::TABLES as $table) {
            $records[$table] = DB::table($table)->orderBy('id')->get()->keyBy('id');
            foreach ($records[$table] as $record) {
                // Permite retomar a migração caso somente uma alteração de esquema tenha falhado.
                if (isset($record->user_id)) {
                    $owners[$table][$record->id][(int) $record->user_id] = true;
                }
            }
        }

        $dados = DB::table('dado_biomedico')->orderBy('id')->get();
        $midias = DB::table('midia')
            ->join('dado_biomedico', 'dado_biomedico.id', '=', 'midia.dado_biomedico_id')
            ->select('midia.id', 'midia.categoria_midia_id', 'dado_biomedico.paciente_id')->get();

        foreach ($dados as $dado) {
            if ($dado->categoria_id !== null) {
                $owners['categoria'][$dado->categoria_id][(int) $dado->paciente_id] = true;
            }
            if ($dado->medico_id !== null) {
                $owners['medico'][$dado->medico_id][(int) $dado->paciente_id] = true;
            }
        }
        foreach ($midias as $midia) {
            $owners['categoria_midia'][$midia->categoria_midia_id][(int) $midia->paciente_id] = true;
        }
        foreach ($records['medico'] as $medico) {
            foreach (array_keys($owners['medico'][$medico->id] ?? []) as $userId) {
                $owners['especialidade'][$medico->especialidade_id][$userId] = true;
            }
        }

        // Não atribui cadastros sem vínculo a uma conta arbitrária e não descarta informações.
        foreach (self::TABLES as $table) {
            foreach ($records[$table] as $record) {
                if (empty($owners[$table][$record->id])) {
                    throw new RuntimeException("Cadastro legado sem proprietário identificável: {$table} #{$record->id}. Defina sua conta antes de executar a migração.");
                }
            }
        }

        foreach (self::TABLES as $table) {
            if (! Schema::hasColumn($table, 'user_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->foreignId('user_id')->nullable()->constrained('users')->restrictOnDelete();
                });
            }
        }

        DB::transaction(function () use ($records, $owners, $dados, $midias) {
            $map = [];
            foreach (['categoria', 'especialidade', 'categoria_midia', 'medico'] as $table) {
                foreach ($records[$table] as $record) {
                    $userIds = array_keys($owners[$table][$record->id]);
                    sort($userIds, SORT_NUMERIC);

                    foreach ($userIds as $index => $userId) {
                        $attributes = (array) $record;
                        unset($attributes['id']);
                        $attributes['user_id'] = $userId;
                        if ($table === 'medico') {
                            $attributes['especialidade_id'] = $map['especialidade'][$record->especialidade_id][$userId];
                        }

                        if ($index === 0) {
                            DB::table($table)->where('id', $record->id)->update($attributes);
                            $map[$table][$record->id][$userId] = $record->id;
                        } else {
                            $map[$table][$record->id][$userId] = DB::table($table)->insertGetId($attributes);
                        }
                    }
                }
            }

            foreach ($dados as $dado) {
                DB::table('dado_biomedico')->where('id', $dado->id)->update([
                    'categoria_id' => $dado->categoria_id === null ? null : $map['categoria'][$dado->categoria_id][$dado->paciente_id],
                    'medico_id' => $dado->medico_id === null ? null : $map['medico'][$dado->medico_id][$dado->paciente_id],
                ]);
            }
            foreach ($midias as $midia) {
                DB::table('midia')->where('id', $midia->id)->update([
                    'categoria_midia_id' => $map['categoria_midia'][$midia->categoria_midia_id][$midia->paciente_id],
                ]);
            }
        });

        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedBigInteger('user_id')->nullable(false)->change();
            });
        }
    }

    public function down(): void
    {
        foreach (array_reverse(self::TABLES) as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropConstrainedForeignId('user_id');
            });
        }
        // As cópias e associações são preservadas; não há mesclagem destrutiva no rollback.
    }
};
