<?php

namespace App\Console\Commands;

use App\Models\Midia;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class ProtegerAnexos extends Command
{
    protected $signature = 'midias:proteger-anexos {--dry-run : Verifica os arquivos sem transferi-los}';

    protected $description = 'Transfere os anexos públicos para o disco privado, verificando a integridade das cópias';

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk('anexos');

        try {
            $this->validateRoots($public, $private);

            // Inclui arquivos sem registro para não deixar anexos antigos expostos.
            $publicPaths = $public->allFiles('midias');
            $paths = Midia::query()->pluck('anexo')->merge($publicPaths)->unique();

            // Verifica todos os caminhos antes de remover qualquer cópia pública.
            foreach ($paths as $path) {
                if (! is_string($path) || ! preg_match('~\Amidias/[A-Za-z0-9][A-Za-z0-9._-]*\z~', $path)) {
                    throw new RuntimeException('Há um caminho de anexo inválido. Nenhum arquivo foi transferido.');
                }

                if (! $public->exists($path) && ! $private->exists($path)) {
                    throw new RuntimeException("Arquivo não encontrado em nenhum dos discos: {$path}");
                }

                if ($public->exists($path) && $private->exists($path)
                    && $this->checksum($public, $path) !== $this->checksum($private, $path)) {
                    throw new RuntimeException("Conteúdos diferentes no mesmo caminho; arquivos preservados: {$path}");
                }
            }

            if ($this->option('dry-run')) {
                $this->info(count($publicPaths).' arquivo(s) público(s) a transferir. Verificação concluída sem alterações.');

                return self::SUCCESS;
            }

            foreach ($publicPaths as $path) {
                if (! $private->exists($path)) {
                    $stream = $public->readStream($path);
                    if (! is_resource($stream)) {
                        throw new RuntimeException("Não foi possível ler o arquivo: {$path}");
                    }

                    try {
                        if (! $private->put($path, $stream)) {
                            throw new RuntimeException("Não foi possível armazenar o arquivo privado: {$path}");
                        }
                    } finally {
                        fclose($stream);
                    }
                }

                if ($this->checksum($public, $path) !== $this->checksum($private, $path)) {
                    throw new RuntimeException("Falha na verificação da cópia; arquivo público preservado: {$path}");
                }

                if (! $public->delete($path)) {
                    throw new RuntimeException("Cópia privada verificada, mas não foi possível remover a pública: {$path}");
                }
            }

            $this->info(count($publicPaths).' arquivo(s) transferido(s) e verificado(s). Os caminhos no banco foram preservados.');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    private function validateRoots(FilesystemAdapter $public, FilesystemAdapter $private): void
    {
        $publicRoot = realpath($public->path(''));
        $privateRoot = realpath($private->path(''));
        $webRoot = realpath(public_path());

        if ($publicRoot === false || $privateRoot === false || $webRoot === false) {
            throw new RuntimeException('Não foi possível verificar os diretórios de armazenamento.');
        }

        $normalize = fn (string $path) => rtrim(strtolower(str_replace('\\', '/', $path)), '/');
        $publicRoot = $normalize($publicRoot);
        $privateRoot = $normalize($privateRoot);
        $webRoot = $normalize($webRoot);

        if ($privateRoot === $publicRoot || str_starts_with($privateRoot.'/', $publicRoot.'/')
            || str_starts_with($publicRoot.'/', $privateRoot.'/')
            || str_starts_with($privateRoot.'/', $webRoot.'/')) {
            throw new RuntimeException('Os discos devem usar diretórios separados, com os anexos fora da raiz pública.');
        }
    }

    private function checksum(FilesystemAdapter $disk, string $path): string
    {
        $stream = $disk->readStream($path);
        if (! is_resource($stream)) {
            throw new RuntimeException("Não foi possível verificar o arquivo: {$path}");
        }

        try {
            $hash = hash_init('sha256');
            if (hash_update_stream($hash, $stream) === false) {
                throw new RuntimeException("Falha na leitura para verificação: {$path}");
            }

            return hash_final($hash);
        } finally {
            fclose($stream);
        }
    }
}
