# Gerenciador Pessoal de Dados Biomédicos

Aplicação web para organizar medições e arquivos de exames em um histórico pessoal. O próprio usuário cadastra o dado biomédico que deseja acompanhar, como o peso corporal, e acrescenta medições com valor, data e hora. Assim, pode consultar exames e comparar medições ao longo do tempo sem repetir o cadastro.

O sistema foi desenvolvido como produto mínimo viável (MVP) no Curso Técnico Integrado em Informática do **IFMG – Campus Formiga**, em 2026. **Autor:** Heythor Daniel Rodrigues de Assis. **Orientador:** Prof. Roger Santos Ferreira.

## Uso da aplicação

1. **Crie uma conta** com nome, e-mail e senha. Após entrar, use o menu **Perfil** para consultar ou atualizar suas informações.
2. **Cadastre um dado biomédico** com nome, valor de referência e unidade de medida. A associação a um médico e a uma categoria é opcional. Cada conta gerencia seus próprios médicos, especialidades, categorias de dados biomédicos e categorias de mídia.
3. **Registre uma medição ou anexe um exame.** A medição guarda o nome, o valor, a data e a hora. O anexo fica associado ao dado biomédico e a uma categoria de mídia.
4. **Consulte o histórico** pelas listagens com busca, filtros e paginação. Elas permitem editar ou excluir registros. No menu **Dashboard**, é possível comparar as quantidades registradas e acompanhar a evolução dos valores numéricos.

<details>
<summary>Requisitos especificados no artigo</summary>

| Requisitos | Escopo |
| --- | --- |
| RF01 a RF03 | Cadastro de conta, autenticação, encerramento da sessão e atualização do perfil. |
| RF04 e RF05 | Gerenciamento dos médicos, especialidades e categorias de cada conta. |
| RF06 a RF08 | Gerenciamento de dados biomédicos, medições e arquivos de exames. |
| RF09 | Busca, filtros e paginação nas listagens. |
| RF10 a RF12 | Indicadores da conta, gráficos de contagem e evolução dos valores por dado biomédico e período. |
| RNF01 e RNF02 | Verificação de pertencimento no servidor, inclusive nas associações entre registros e no acesso aos anexos privados. |
| RNF03 e RNF04 | Armazenamento das senhas como *hash*, validação das entradas e integridade referencial no banco. |
| RNF05 e RNF06 | Adaptação das interfaces a diferentes larguras, navegação e paginação em português brasileiro, identificação dos campos e mensagens sobre as operações. |
| RNF07 a RNF09 | Separação entre modelos, visões e controladores, migrações do banco, versionamento com Git, testes em ambiente separado e execução local pelo navegador. |

</details>

## Dashboard

Os cartões mostram o total de dados biomédicos da conta, o total de medições e a data e a hora da última medição. O painel usa Chart.js para apresentar quatro gráficos.

| Gráfico | O que mostra |
| --- | --- |
| Evolução das medições | Cada ponto representa um valor numérico do dado selecionado, na data e na hora da medição. O filtro de período inclui as datas inicial e final. |
| Medições por dia | Uma linha por dado biomédico. Nas datas em que a conta tem medições, cada linha mostra sua própria contagem, inclusive zero. |
| Dados por categoria | Distribuição dos dados biomédicos por categoria. Os registros sem classificação aparecem em “Sem categoria”. |
| Medições por dado biomédico | Uma barra para cada dado biomédico com pelo menos uma medição. |

O filtro de período afeta o gráfico de evolução e seu contador. Uma medição com valor composto, como `120/80`, permanece na listagem e entra no total do período. Apenas valores que representam um único número geram pontos no gráfico, inclusive decimais escritos com vírgula ou ponto.

## Arquivos de exames e acesso por conta

O módulo de mídias aceita **JPEG, PNG e PDF de até 5.120 KB por arquivo**. O conteúdo fica em `storage/app/private/anexos/midias`. O MySQL guarda o caminho relativo e as associações do anexo.

Ao abrir **Visualizar**, o servidor verifica a sessão e se o dado biomédico associado pertence à conta. Isso também se aplica ao acesso direto pelo link. Um visitante é encaminhado ao login e outra conta autenticada recebe a resposta HTTP 404. Ao substituir um arquivo, a aplicação preserva o original até que o novo caminho seja gravado no banco.

## Ambiente e execução local

| Componente | Ambiente utilizado no projeto |
| --- | --- |
| Aplicação | Windows, PHP 8.2.12, Laravel 12.36.1 e Eloquent |
| Banco de dados | MySQL Community Server 8.0.42 |
| Interface | Blade, Bootstrap 5.3.2, Medilab e Chart.js |
| Dependências e recursos da interface | Composer, Node.js, npm e Vite 7.1.12 |
| Testes | PHPUnit 11.5.43 e SQLite em memória |

Para preparar a aplicação, consulte o [guia de execução local](docs/EXECUCAO_LOCAL.md). Com as dependências instaladas e a conexão com o MySQL configurada no arquivo `.env`, inicie o servidor na raiz do projeto e abra o endereço indicado no terminal em um navegador com JavaScript habilitado.

```shell
php artisan serve
```

## Testes e resultados

Para executar a suíte na raiz do projeto, use o comando abaixo. A configuração dos testes está em `phpunit.xml`.

```shell
php artisan test
```

A execução relatada no artigo registrou **90 testes aprovados e 481 asserções**. Dos 90 testes, 89 verificaram comportamentos da aplicação e um correspondeu ao teste unitário de exemplo. Os cenários abrangeram o cadastro de usuários, o isolamento entre contas, o gerenciamento de anexos privados e a preparação dos dados dos gráficos.

Os testes de funcionalidade utilizaram SQLite em memória e armazenamento simulado para os anexos. A conferência visual abrangeu telas representativas no Chrome nas larguras de 320, 360, 440 e 1280 pixels. Os trabalhos futuros incluem testar as operações com MySQL e as interações com os gráficos no navegador, além de avaliar com usuários as tarefas de registrar uma medição, localizar um exame e interpretar um gráfico.
