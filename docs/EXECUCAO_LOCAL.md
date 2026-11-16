# Execução Local

## Pré-requisitos
- PHP 8.2+
- Composer 2+
- Node.js 18+
- NPM
- SQLite ou MySQL

## Passo a passo
Na pasta `gerenciador-dadosbiomedicos`:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Defina no `.env` para ambiente local:

```env
APP_URL=http://127.0.0.1:8000
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

## Banco SQLite (recomendado)
1. Criar arquivo `database/database.sqlite` (se não existir).
2. Configurar `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

3. Executar:

```bash
php artisan migrate:fresh
php artisan serve
```

## Banco MySQL (alternativo)
1. Criar banco (ex.: `gerenciador`).
2. Configurar `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gerenciador
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

3. Executar:

```bash
php artisan migrate:fresh
php artisan serve
```

## Acesso
- URL: `http://127.0.0.1:8000`
- Primeiro acesso: criar usuário em `/register`

## Anexos privados

Os arquivos enviados ficam em `storage/app/private/anexos/midias`. A opção “Visualizar” utiliza uma rota autenticada que permite a abertura apenas pelo proprietário do registro. Os anexos não precisam de `storage:link`.

Ao atualizar uma instalação com anexos públicos, siga a sequência da seção 5.4 do [README](../README.md), usando `midias:proteger-anexos --dry-run` antes da transferência. Os caminhos registrados no banco são preservados. Não utilize `migrate:fresh` para atualizar um banco com dados existentes.

## Cadastros de apoio por usuário

Médicos, especialidades, categorias e categorias de mídia possuem proprietário. Ao atualizar uma instalação, faça backup e execute `php artisan migrate`, conforme a seção 5.5 do [README](../README.md). A migração preserva os vínculos existentes e separa os cadastros compartilhados em cópias por conta. Uma conta nova começa sem esses cadastros e pode criar os próprios.

## Comandos úteis
```bash
php artisan route:list
php artisan optimize:clear
php artisan test
```

## Problemas comuns
- `php: command not found`:
  - Windows: usar caminho completo do PHP (ex.: `C:\php-8.3\php.exe artisan serve`)
  - Linux/WSL: adicionar PHP no `PATH`.
- Erro de banco:
  - conferir credenciais no `.env`;
  - rodar `php artisan optimize:clear` e repetir `migrate:fresh`.
