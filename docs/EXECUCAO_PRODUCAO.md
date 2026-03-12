# Execução em Produção

Guia para publicar o sistema em ambiente de produção com foco em segurança, estabilidade e manutenção.

## 1. Pré-requisitos
- PHP 8.2+ (recomendado 8.3)
- Composer 2+
- Node.js 18+
- MySQL 8+
- Servidor web (Nginx ou Apache)
- Supervisor (para filas, se necessário)
- Certificado HTTPS (Let's Encrypt ou equivalente)

## 2. Checklist de infraestrutura
- Servidor Linux com acesso SSH.
- Banco MySQL criado e com usuário exclusivo da aplicação.
- Domínio apontando para o servidor.
- HTTPS habilitado.
- Firewall ativo (liberar apenas portas necessárias).

## 3. Deploy inicial
Na pasta do projeto no servidor:

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
cp .env.example .env
php artisan key:generate
```

## 4. Configuração do `.env` para produção
Exemplo base (ajustar valores reais):

```env
APP_NAME="Gerenciador Biomédico"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gerenciador
DB_USERNAME=gerenciador_user
DB_PASSWORD=senha_forte

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

Observações:
- Em produção, `APP_DEBUG` deve ficar `false`.
- Não versionar `.env` em repositório.

## 5. Banco de dados
```bash
php artisan migrate --force
```

Se for primeiro deploy e houver necessidade de reset completo:
```bash
php artisan migrate:fresh --force
```

## 6. Ajustes de aplicação
```bash
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 7. Permissões (Linux)
Ajustar usuário do servidor web (ex.: `www-data`):

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 8. Nginx (exemplo de bloco server)
```nginx
server {
    listen 80;
    server_name seu-dominio.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name seu-dominio.com;

    root /var/www/gerenciador-dadosbiomedicos/public;
    index index.php;

    ssl_certificate /etc/letsencrypt/live/seu-dominio.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/seu-dominio.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

## 9. Deploy de atualização
Sequência recomendada:

```bash
git pull
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 10. Testes pós-deploy
- Acessar `/login` e `/register`.
- Criar usuário de teste.
- Validar fluxo básico:
  - especialidade -> médico -> categoria -> dado biomédico -> medição -> mídia.
- Verificar anexos de mídia abrindo via `storage`.

## 11. Monitoramento e logs
- Log Laravel: `storage/logs/laravel.log`
- Monitorar erro 500/419.
- Rotacionar logs no servidor.

## 12. Backup e rollback
- Backup diário do banco MySQL.
- Backup de `storage/app/public` (anexos).
- Em rollback de release:
  - restaurar código da versão anterior;
  - restaurar backup do banco se necessário.

## 13. Segurança mínima recomendada
- Forçar HTTPS.
- Senhas fortes para banco e SSH.
- Usuário de banco com privilégios mínimos.
- Atualizar dependências periodicamente.
- Desabilitar acesso público a arquivos sensíveis fora de `public/`.
