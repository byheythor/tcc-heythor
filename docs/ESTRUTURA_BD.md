# Estrutura do Banco de Dados

Referência atual da estrutura: migrações em `database/migrations`. O arquivo `modelo-bd.mwb` representa a modelagem anterior à individualização dos cadastros de apoio.

Observação: este documento descreve as tabelas de domínio do sistema.

## Tabelas

## users
- `id` (PK)
- `data_nascimento`
- `nome`
- `email` (unique)
- `cpf` (unique, nullable)
- `celular` (nullable)
- `password`
- `remember_token`
- `created_at`, `updated_at`

## categoria
- `id` (PK)
- `nome`
- `user_id` (FK -> `users.id`, obrigatório)

## categoria_midia
- `id` (PK)
- `nome`
- `user_id` (FK -> `users.id`, obrigatório)

## especialidade
- `id` (PK)
- `nome`
- `user_id` (FK -> `users.id`, obrigatório)

## medico
- `id` (PK)
- `nome`
- `crm` (nullable)
- `contato` (nullable)
- `especialidade_id` (FK -> `especialidade.id`)
- `user_id` (FK -> `users.id`, obrigatório)

## dado_biomedico
- `id` (PK)
- `nome`
- `medico_id` (FK -> `medico.id`, nullable)
- `paciente_id` (FK -> `users.id`)
- `categoria_id` (FK -> `categoria.id`, nullable)
- `valor_referencia`
- `unidade_medida`

## medicao
- `id` (PK)
- `nome`
- `valor`
- `dado_biomedico_id` (FK -> `dado_biomedico.id`)
- `data_hora`

## midia
- `id` (PK)
- `anexo`
- `tipo` (nullable)
- `dado_biomedico_id` (FK -> `dado_biomedico.id`)
- `categoria_midia_id` (FK -> `categoria_midia.id`)

## Relacionamentos
- `users 1:N dado_biomedico`
- `users 1:N categoria`
- `users 1:N especialidade`
- `users 1:N medico`
- `users 1:N categoria_midia`
- `especialidade 1:N medico`
- `medico 1:N dado_biomedico`
- `categoria 1:N dado_biomedico`
- `dado_biomedico 1:N medicao`
- `dado_biomedico 1:N midia`
- `categoria_midia 1:N midia`

## Regra de propriedade
O dono dos dados é definido por `dado_biomedico.paciente_id`.

- `medicao` e `midia` pertencem indiretamente ao usuário via `dado_biomedico`.
- `categoria`, `categoria_midia`, `especialidade` e `medico` pertencem diretamente a uma conta por `user_id`.
- O servidor valida que as associações entre médicos/especialidades, dados biomédicos/médicos/categorias e mídias/categorias de mídia utilizem registros da mesma conta.
- Cadastros anteriormente utilizados por várias contas são separados em cópias independentes durante a migração, preservando as associações e os atributos.
