<?php

namespace Database\Seeders;

use App\Models\DadoBiomedico;
use App\Models\Medicao;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DashboardTestSeeder extends Seeder
{
    /**
     * Seed dashboard data for manual chart testing.
     */
    public function run(): void
    {
        $paciente = User::firstOrCreate(
            ['email' => 'dashboard.teste@example.com'],
            [
                'nome' => 'Paciente Teste Dashboard',
                'cpf' => null,
                'celular' => '11999990000',
                'data_nascimento' => '1990-04-12',
                'password' => Hash::make('12345678'),
            ]
        );

        $cardiovascular = $paciente->categorias()->firstOrCreate(['nome' => 'Cardiovascular']);
        $metabolico = $paciente->categorias()->firstOrCreate(['nome' => 'Metabólico']);
        $antropometria = $paciente->categorias()->firstOrCreate(['nome' => 'Antropometria']);

        $clinicaGeral = $paciente->especialidades()->firstOrCreate(['nome' => 'Clínica geral']);
        $cardiologia = $paciente->especialidades()->firstOrCreate(['nome' => 'Cardiologia']);

        $medicoClinico = $paciente->medicos()->firstOrCreate(
            ['crm' => 'CRM-SP 123456'],
            [
                'nome' => 'Dra. Ana Martins',
                'contato' => '(11) 3333-1111',
                'especialidade_id' => $clinicaGeral->id,
            ]
        );

        $medicoCardio = $paciente->medicos()->firstOrCreate(
            ['crm' => 'CRM-SP 654321'],
            [
                'nome' => 'Dr. Bruno Lima',
                'contato' => '(11) 3333-2222',
                'especialidade_id' => $cardiologia->id,
            ]
        );

        $dados = [
            [
                'nome' => 'Pressão arterial sistólica',
                'categoria_id' => $cardiovascular->id,
                'medico_id' => $medicoCardio->id,
                'valor_referencia' => 'Até 120',
                'unidade_medida' => 'mmHg',
                'valores' => [118, 122, 119, 125, 121, 117],
            ],
            [
                'nome' => 'Frequência cardíaca',
                'categoria_id' => $cardiovascular->id,
                'medico_id' => $medicoCardio->id,
                'valor_referencia' => '60 a 100',
                'unidade_medida' => 'bpm',
                'valores' => [78, 82, 76, 80, 74, 79],
            ],
            [
                'nome' => 'Glicemia',
                'categoria_id' => $metabolico->id,
                'medico_id' => $medicoClinico->id,
                'valor_referencia' => '70 a 99',
                'unidade_medida' => 'mg/dL',
                'valores' => [94, 101, 97, 92, 105, 99],
            ],
            [
                'nome' => 'Colesterol total',
                'categoria_id' => $metabolico->id,
                'medico_id' => $medicoClinico->id,
                'valor_referencia' => 'Até 190',
                'unidade_medida' => 'mg/dL',
                'valores' => [182, 188, 176, 191, 184, 179],
            ],
            [
                'nome' => 'Peso corporal',
                'categoria_id' => $antropometria->id,
                'medico_id' => $medicoClinico->id,
                'valor_referencia' => 'Individual',
                'unidade_medida' => 'kg',
                'valores' => [82.4, 82.1, 81.9, 81.7, 81.4, 81.2],
            ],
        ];

        $nomesDados = collect($dados)->pluck('nome');

        DadoBiomedico::where('paciente_id', $paciente->id)
            ->whereIn('nome', $nomesDados)
            ->get()
            ->each(function (DadoBiomedico $dadoBiomedico) {
                $dadoBiomedico->medicoes()->delete();
                $dadoBiomedico->delete();
            });

        foreach ($dados as $indiceDado => $dado) {
            $dadoBiomedico = DadoBiomedico::create([
                'nome' => $dado['nome'],
                'medico_id' => $dado['medico_id'],
                'paciente_id' => $paciente->id,
                'categoria_id' => $dado['categoria_id'],
                'valor_referencia' => $dado['valor_referencia'],
                'unidade_medida' => $dado['unidade_medida'],
            ]);

            foreach ($dado['valores'] as $indiceValor => $valor) {
                Medicao::create([
                    'nome' => 'Medição de '.$dado['nome'],
                    'valor' => (string) $valor,
                    'dado_biomedico_id' => $dadoBiomedico->id,
                    'data_hora' => Carbon::now()
                        ->subDays((count($dado['valores']) - $indiceValor - 1) * 2 + $indiceDado)
                        ->setTime(8 + ($indiceValor % 4), 0),
                ]);
            }
        }
    }
}
