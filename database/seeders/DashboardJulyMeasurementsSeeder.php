<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardJulyMeasurementsSeeder extends Seeder
{
    /**
     * Add synthetic July 2026 readings to the existing dashboard demo account.
     * Existing readings and the media-only Hemograma record are preserved.
     */
    public function run(): void
    {
        $days = [1, 2, 4, 7, 10, 13, 16, 19, 22, 25, 28, 31];
        $times = ['07:30:00', '13:00:00', '19:30:00'];
        $periods = ['manhã', 'tarde', 'noite'];
        $series = [
            'Colesterol total' => [
                'unit' => 'mg/dL',
                'counts' => [1, 0, 2, 1, 3, 0, 1, 2, 1, 3, 1, 2],
                'values' => [178, 184, 181, 193, 187, 176, 189, 182, 195, 180, 186, 179],
            ],
            'Frequência cardíaca' => [
                'unit' => 'bpm',
                'counts' => [2, 3, 1, 2, 0, 3, 1, 2, 3, 1, 2, 3],
                'values' => [74, 81, 77, 86, 72, 79, 83, 76, 88, 75, 80, 78],
            ],
            'Glicemia' => [
                'unit' => 'mg/dL',
                'counts' => [1, 3, 2, 1, 3, 2, 0, 3, 1, 2, 3, 1],
                'values' => [91, 103, 96, 108, 94, 100, 89, 105, 98, 110, 93, 101],
            ],
            'Peso corporal' => [
                'unit' => 'kg',
                'counts' => [1, 2, 0, 1, 2, 1, 3, 1, 0, 2, 1, 2],
                'values' => [81.3, 81.1, 81.4, 81.0, 80.9, 81.2, 80.8, 81.0, 80.7, 80.9, 80.6, 80.8],
            ],
            'Pressão arterial sistólica' => [
                'unit' => 'mmHg',
                'counts' => [3, 1, 2, 3, 1, 0, 2, 1, 3, 2, 1, 3],
                'values' => [116, 123, 119, 127, 121, 114, 125, 118, 129, 120, 117, 124],
            ],
        ];

        DB::transaction(function () use ($days, $times, $periods, $series) {
            $user = User::where('email', 'dashboard.teste@example.com')->lockForUpdate()->sole();
            $records = [];

            // Check every target before inserting; never create or replace biomedical records.
            foreach ($series as $name => $settings) {
                $records[$name] = $user->dadosBiomedicos()->where('nome', $name)->lockForUpdate()->sole();
                if ($records[$name]->unidade_medida !== $settings['unit']) {
                    throw new \RuntimeException('Unidade inesperada para '.$name.'. Nenhuma medição foi adicionada.');
                }
            }

            foreach ($series as $name => $settings) {
                foreach ($days as $index => $day) {
                    for ($slot = 0; $slot < $settings['counts'][$index]; $slot++) {
                        $measurementName = 'Medição de '.$name;
                        $date = sprintf('2026-07-%02d %s', $day, $times[$slot]);

                        // Rename the original demo labels without duplicating or changing readings.
                        $records[$name]->medicoes()
                            ->where('data_hora', $date)
                            ->where('nome', 'Exemplo julho/2026 - '.$periods[$slot])
                            ->update(['nome' => $measurementName]);

                        $records[$name]->medicoes()->firstOrCreate([
                            'nome' => $measurementName,
                            'data_hora' => $date,
                        ], [
                            'valor' => (string) $settings['values'][($index + $slot * 3) % count($settings['values'])],
                        ]);
                    }
                }
            }
        });
    }
}
