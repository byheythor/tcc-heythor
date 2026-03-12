# Gerenciador Pessoal de Dados Biomédicos (Laravel)

Sistema web para gerenciamento de dados biomédicos pessoais, com autenticação e CRUD de domínio médico.

## 1. Visão geral

O sistema permite:

- Cadastro e login de usuário (paciente).
- Cadastro de categorias e categorias de mídia.
- Cadastro de especialidades e médicos.
- Cadastro de dados biomédicos vinculados ao paciente logado.
- Registro de medições vinculadas a um dado biomédico.
- Upload de mídias (imagem/PDF) vinculadas a um dado biomédico.
- Filtros de listagem por relacionamento.

## 2. Stack e requisitos

- PHP `8.2+` (recomendado `8.3`)
- Composer `2+`
- Node.js `18+` (ou `20+`)
- NPM
- Banco de dados:
    - SQLite (recomendado para ambiente local rápido), ou
    - MySQL 8+

Frameworks e libs principais:

- Laravel `12`
- Laravel UI (autenticação)
- Bootstrap (tema Medilab adaptado)

## 3. Estrutura do projeto

- `app/Models`: entidades de domínio.
- `app/Http/Controllers`: controladores dos módulos CRUD.
- `database/migrations`: estrutura oficial do banco.
- `resources/views`: telas Blade do sistema.
- `public/assets`: arquivos estáticos do tema.
- `routes/web.php`: rotas web autenticadas.

## 4. Estrutura do banco de dados

Modelagem do banco

### 4.1 Tabelas de domínio

- `users`
    - `id`, `data_nascimento`, `nome`, `email`, `cpf`, `celular`, `password`...
- `categoria`
    - `id`, `nome`
- `categoria_midia`
    - `id`, `nome`
- `especialidade`
    - `id`, `nome`
- `medico`
    - `id`, `nome`, `crm`, `contato`, `especialidade_id`
- `dado_biomedico`
    - `id`, `nome`, `medico_id`, `paciente_id`, `categoria_id`, `valor_referencia`, `unidade_medida`
- `medicao`
    - `id`, `nome`, `valor`, `dado_biomedico_id`, `data_hora`
- `midia`
    - `id`, `anexo`, `tipo`, `dado_biomedico_id`, `categoria_midia_id`

### 4.2 Relacionamentos principais

- `users (1) -> (N) dado_biomedico`
- `medico (1) -> (N) dado_biomedico`
- `categoria (1) -> (N) dado_biomedico`
- `especialidade (1) -> (N) medico`
- `dado_biomedico (1) -> (N) medicao`
- `dado_biomedico (1) -> (N) midia`
- `categoria_midia (1) -> (N) midia`

### 4.3 Controle de acesso aos dados

A propriedade dos dados do usuário é controlada por `dado_biomedico.paciente_id`.

- Módulos `medicao` e `midia` são filtrados via relacionamento com `dado_biomedico` do usuário logado.
- Módulos de apoio (`categoria`, `especialidade`, `medico`, `categoria_midia`) são globais no sistema.

## 5. Como executar localmente

### 5.1 Instalação

Na pasta `gerenciador-dadosbiomedicos`:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Ajuste no `.env` para desenvolvimento local:

```env
APP_URL=http://127.0.0.1:8000
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### 5.2 Banco com SQLite

1. Garanta que exista o arquivo `database/database.sqlite`.
2. No `.env`, configure:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

3. Rode as migrations:

```bash
php artisan migrate:fresh
```

### 5.3 Banco com MySQL

1. Crie um banco vazio no MySQL (ex.: `gerenciador`).
2. Ajuste `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gerenciador
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

3. Rode as migrations:

```bash
php artisan migrate:fresh
```

### 5.4 Storage de upload

Necessário para visualização de anexos de mídia:

```bash
php artisan storage:link
```

### 5.5 Subir a aplicação

```bash
php artisan serve
```

Acesse:

- `http://127.0.0.1:8000`

## 6. Fluxo de uso recomendado

1. Registrar usuário (`/register`).
2. Cadastrar especialidade.
3. Cadastrar médico.
4. Cadastrar categoria.
5. Cadastrar dado biomédico.
6. Cadastrar medição.
7. Cadastrar categoria de mídia.
8. Fazer upload de mídia.

## 7. Comandos úteis

```bash
php artisan route:list
php artisan optimize:clear
php artisan test
```

## 8. Qualidade e validação

Checklist rápido de validação funcional:

1. Registrar usuário em `/register`.
2. Criar especialidade.
3. Criar médico vinculado à especialidade.
4. Criar categoria e dado biomédico.
5. Registrar medição (appointment).
6. Criar categoria de mídia e enviar um anexo.
7. Confirmar que dados de outro usuário não aparecem nas listagens de `dado_biomedico`, `medicao` e `midia`.

Testes automatizados do projeto:

- `tests/Feature/AuthAndCrudFlowTest.php`
- `tests/Feature/ExampleTest.php`

## 9. Observações importantes

- Antes de entregar em produção, recomenda-se:
    - adicionar seeders de dados iniciais;
    - revisar autorização por policy;
    - trocar rotas de exclusão para `DELETE` com CSRF.

## 10. Responsável pelo projeto

Para manutenção, priorizar leitura de:

1. `README.md` (este arquivo)
2. `docs/EXECUCAO_LOCAL.md`
3. `docs/EXECUCAO_PRODUCAO.md`
4. `docs/ESTRUTURA_BD.md`
5. `database/migrations/`
6. `app/Models/`
7. `app/Http/Controllers/`
8. `routes/web.php`
