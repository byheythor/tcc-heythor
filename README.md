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

## 5. Execução local

### 5.1 Instalação

composer install
npm install
cp .env.example .env
php artisan key:generate

Ajuste no `.env` para desenvolvimento local:

```env
APP_URL=http://127.0.0.1:8000
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### 5.2 Banco com MySQL

1. Crie um banco vazio no MySQL.
2. Ajuste `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database
DB_USERNAME=usuario
DB_PASSWORD=senha
```

3. Rode as migrations:

```bash
php artisan migrate:fresh
```

### 5.3 Subir a aplicação

```bash
php artisan serve
```

Acesse:

- `http://127.0.0.1:8000`

## 6. Fluxo de uso recomendado/Qualidade e validação

Checklist rápido de validação funcional:

1. Registrar usuário em `/register`.
2. Criar especialidade.
3. Criar médico vinculado à especialidade.
4. Criar categoria e dado biomédico.
5. Registrar medição.
6. Criar categoria de mídia e enviar um anexo.