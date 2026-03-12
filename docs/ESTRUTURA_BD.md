# Estrutura do Banco de Dados

Referência de modelagem: `modelo-bd.mwb`.

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

## categoria_midia
- `id` (PK)
- `nome`

## especialidade
- `id` (PK)
- `nome`

## medico
- `id` (PK)
- `nome`
- `crm` (nullable)
- `contato` (nullable)
- `especialidade_id` (FK -> `especialidade.id`)

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
- `especialidade 1:N medico`
- `medico 1:N dado_biomedico`
- `categoria 1:N dado_biomedico`
- `dado_biomedico 1:N medicao`
- `dado_biomedico 1:N midia`
- `categoria_midia 1:N midia`

## Regra de propriedade
O dono dos dados é definido por `dado_biomedico.paciente_id`.

- `medicao` e `midia` pertencem indiretamente ao usuário via `dado_biomedico`.
- `categoria`, `categoria_midia`, `especialidade` e `medico` são tabelas de apoio globais.
