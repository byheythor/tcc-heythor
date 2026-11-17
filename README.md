## 1. Visão geral

O sistema permite:

- Cadastro e login de usuário (paciente).
- Cadastro de categorias e categorias de mídia.
- Cadastro de especialidades e médicos.
- Cadastro de dados biomédicos vinculados ao paciente logado.
- Registro de medições vinculadas a um dado biomédico.
- Upload de mídias (imagem/PDF) vinculadas a um dado biomédico.
- Filtros de listagem por relacionamento.
- Dashboard de contagens e evolução dos valores das medições por dado biomédico e período.

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
    - `id`, `nome`, `user_id`
- `categoria_midia`
    - `id`, `nome`, `user_id`
- `especialidade`
    - `id`, `nome`, `user_id`
- `medico`
    - `id`, `nome`, `crm`, `contato`, `especialidade_id`, `user_id`
- `dado_biomedico`
    - `id`, `nome`, `medico_id`, `paciente_id`, `categoria_id`, `valor_referencia`, `unidade_medida`
- `medicao`
    - `id`, `nome`, `valor`, `dado_biomedico_id`, `data_hora`
- `midia`
    - `id`, `anexo`, `tipo`, `dado_biomedico_id`, `categoria_midia_id`

### 4.2 Relacionamentos principais

- `users (1) -> (N) dado_biomedico`
- `users (1) -> (N) categoria / especialidade / medico / categoria_midia`
- `medico (1) -> (N) dado_biomedico`
- `categoria (1) -> (N) dado_biomedico`
- `especialidade (1) -> (N) medico`
- `dado_biomedico (1) -> (N) medicao`
- `dado_biomedico (1) -> (N) midia`
- `categoria_midia (1) -> (N) midia`

### 4.3 Controle de acesso aos dados

A propriedade dos dados do usuário é controlada por `dado_biomedico.paciente_id`.

- Módulos `medicao` e `midia` são filtrados via relacionamento com `dado_biomedico` do usuário logado.
- Os cadastros de apoio (`categoria`, `especialidade`, `medico`, `categoria_midia`) pertencem a uma conta pelo campo obrigatório `user_id`. As listagens, buscas, formulários, filtros, edições e exclusões utilizam apenas os cadastros do usuário autenticado.
- A conta proprietária é definida no servidor. A validação também exige que o médico, a especialidade e as categorias selecionados pertençam à mesma conta, impedindo associações entre usuários por IDs enviados manualmente.
- Anexos de exames são armazenados no disco privado `anexos`, em `storage/app/private/anexos/midias`. A visualização usa `GET /midia/{id}/anexo`, com autenticação e verificação do proprietário do dado biomédico. Outra conta recebe HTTP 404; visitantes são encaminhados ao login.

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

### 5.4 Atualização de anexos anteriormente públicos

Para uma instalação existente, após atualizar o código, pause os acessos durante a transferência:

```bash
php artisan down
php artisan config:clear
php artisan route:clear
php artisan midias:proteger-anexos --dry-run
php artisan midias:proteger-anexos
php artisan up
```

O comando transfere os arquivos de `storage/app/public/midias` para o disco privado, incluindo arquivos sem registro. Confere o conteúdo com SHA-256 antes de remover cada cópia pública, preservando os caminhos relativos no banco. Pode ser executado novamente e interrompe a operação ao encontrar arquivos ausentes ou conteúdos conflitantes. Resolva os erros apresentados antes de liberar o acesso. O modo de manutenção não bloqueia arquivos estáticos enquanto as cópias públicas ainda existem.

Após a transferência, os endereços antigos `/storage/midias/...` deixam de disponibilizar os arquivos. O link `public/storage`, se utilizado por outros recursos, pode permanecer; não crie links públicos para `storage/app/private`. Inclua a pasta privada no backup. Para instalações novas, os uploads já são privados e não dependem de `storage:link`.

Os testes de anexos e transferência usam armazenamento simulado e SQLite em memória:

```bash
php artisan test
```

### 5.5 Individualização dos cadastros de apoio existentes

A migração `2026_11_16_000001_scope_support_records_to_users` adiciona `user_id` com chave estrangeira para `users` às quatro tabelas de apoio. Em uma instalação existente, faça backup do banco e execute `php artisan migrate` com os acessos temporariamente pausados.

Os vínculos com dados biomédicos e mídias identificam as contas que já utilizam cada cadastro. Registros utilizados por várias contas recebem cópias independentes, e suas associações são ajustadas para preservar as informações de cada usuário. Médicos e suas especialidades permanecem na mesma conta. Medições, conteúdo dos anexos e informações das contas não são alterados.

Se houver um cadastro legado sem vínculo que permita identificar seu proprietário, a migração interrompe antes de alterar o esquema. Esse caso exige definir explicitamente a destinação do cadastro; a migração não o apaga nem escolhe uma conta arbitrária. Novos cadastros já recebem o proprietário no momento da criação, mesmo antes de serem utilizados.

O rollback remove as novas colunas, mas preserva as cópias e os relacionamentos resultantes. Para recuperar exatamente a organização anterior, utilize o backup anterior à migração.

## 6. Fluxo de uso recomendado/Qualidade e validação

Checklist rápido de validação funcional:

1. Registrar usuário em `/register`.
2. Criar especialidade.
3. Criar médico vinculado à especialidade.
4. Criar categoria e dado biomédico.
5. Registrar medição.
6. Criar categoria de mídia e enviar um anexo.

## 7. Evolução dos valores das medições

O gráfico **Medições por dia** apresenta uma linha por dado biomédico da conta, com cores e legendas próprias. Cada ponto indica a quantidade de registros daquele dado na data, incluindo medições textuais. As linhas compartilham as datas em ordem cronológica; quando um dado não tem medições em uma dessas datas, sua contagem é zero. Dados ainda sem medições também são incluídos com contagens zero. Dias sem nenhuma medição na conta não são acrescentados ao eixo. A legenda permite exibir ou ocultar cada linha.

Em `/home`, o painel **Evolução das medições** permite selecionar um dado biomédico da conta e, opcionalmente, uma data inicial e uma data final. Os limites incluem o dia inteiro. **Todo o histórico** remove os filtros de data e mantém o dado selecionado. Esses filtros afetam somente o novo painel; os indicadores e gráficos de contagens continuam representando o histórico da conta.

Cada medição numérica aparece como um ponto, em ordem de data e hora. Medições do mesmo dia ou horário são preservadas individualmente. O eixo temporal respeita os intervalos entre registros, e os detalhes do ponto mostram nome, valor original, unidade e data e hora. A unidade é a cadastrada no dado biomédico. O gráfico utiliza o mesmo fuso configurado na aplicação para evitar deslocamentos provocados pelo fuso do navegador. Uma única medição aparece como um ponto; dias sem registro não recebem valores zero.

O campo `medicao.valor` continua textual, sem migração ou alteração dos registros existentes. Para o gráfico, são aceitos números inteiros ou decimais, com vírgula ou ponto como separador decimal, sinal opcional e espaços nas extremidades. Não são inferidos separadores de milhar, unidades, faixas, notação científica ou valores compostos. Exemplos como `82,4`, `82.4`, `0` e `-0,5` são representados; `positivo`, `120/80`, `95 mg/dL` e `1.234,56` continuam apenas na listagem. O painel informa quantos registros do período não puderam ser representados.

A renderização mantém o Chart.js 4.4.7 e utiliza Luxon 3.5.0 com chartjs-adapter-luxon 1.3.1, carregados por CDN. Não há interpretação clínica automática nem conversão de unidades. Os testes incluem isolamento entre contas, limites de datas, ordenação, estados sem dados e conversão dos valores:

```bash
php artisan test --filter="MeasurementEvolutionTest|MeasurementValueTest"
```

## 8. Medições de demonstração em julho de 2026

Para a conta existente `dashboard.teste@example.com`, o seeder abaixo acrescenta 100 medições sintéticas em julho de 2026. Utiliza os cinco dados biomédicos já cadastrados, com quantidades de 1 a 3 por dia em datas variadas e valores numéricos diferentes. O Hemograma permanece reservado às mídias. Os registros anteriores são preservados, e repetir o comando não duplica o lote.

```bash
php artisan db:seed --class=DashboardJulyMeasurementsSeeder
```

O lote contém 17 medições de colesterol total, 23 de frequência cardíaca, 22 de glicemia, 16 de peso corporal e 22 de pressão arterial sistólica. A conta e os dados biomédicos precisam existir previamente, com suas unidades de medida esperadas.
