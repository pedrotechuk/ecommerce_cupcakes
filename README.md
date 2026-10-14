# Cupcakes Shop 🧁

Loja virtual de cupcakes construída com Laravel 10, Blade, Eloquent e MySQL, executada inteiramente via Docker.

## Stack

- Laravel 10 (PHP 8.2, PHP-FPM)
- MySQL 8.0
- Nginx (servidor web)
- Blade + Tailwind CSS + Alpine.js (via Laravel Breeze)
- Mailpit (captura de e-mails em desenvolvimento)
- Chart.js via CDN (apenas para os gráficos do dashboard administrativo)

## Subindo o ambiente

1. Copie o arquivo de variáveis de ambiente:

   ```bash
   cp .env.example .env
   ```

2. Suba os containers:

   ```bash
   docker compose up -d --build
   ```

   O container `app` aguarda o MySQL ficar disponível, roda as migrations automaticamente e cria o link de storage público na inicialização.

3. Gere a chave da aplicação (apenas na primeira vez, se ainda não estiver definida no `.env`):

   ```bash
   docker compose exec app php artisan key:generate
   ```

4. Popule o banco com dados de demonstração:

   ```bash
   docker compose exec app php artisan migrate:fresh --seed
   ```

5. (Opcional, para desenvolvimento com hot-reload de CSS/JS) suba o container do Vite:

   ```bash
   docker compose up -d node
   ```

## Acessando a aplicação

| Serviço          | URL                              |
|------------------|-----------------------------------|
| Loja             | http://localhost:8080            |
| Mailpit (e-mails)| http://localhost:8025            |
| Vite dev server  | http://localhost:5173            |
| MySQL (host)     | localhost:3307                   |

## Credenciais de demonstração

> ⚠️ **Uso exclusivo de desenvolvimento/avaliação.** Essas contas são criadas pelo `AdminUserSeeder`/`CustomerSeeder` e nunca devem ser usadas em produção.

| Papel     | E-mail                    | Senha      |
|-----------|---------------------------|------------|
| Admin     | admin@cupcakes.test      | password   |
| Cliente   | ana@cupcakes.test        | password   |
| Cliente   | bruno@cupcakes.test      | password   |
| Cliente   | carla@cupcakes.test      | password   |

Cupons de teste já cadastrados pelo `CouponSeeder`: `BEMVINDO10` (10% off), `FRETE5` (R$ 5 off) e `EXPIRADO` (expirado, para testar rejeição).

## Estrutura de containers

- `app`: PHP-FPM 8.2 rodando a aplicação Laravel.
- `webserver`: Nginx servindo a pasta `public/` e fazendo proxy para o `app` via FastCGI.
- `db`: MySQL 8.0 com volume nomeado (dados persistem entre reinicializações).
- `node`: Vite em modo dev, com hot-reload de assets (Tailwind/Alpine).
- `mailpit`: captura os e-mails de confirmação de pedido enviados pela aplicação, sem necessidade de um SMTP real.

Todos os serviços se comunicam pela rede Docker `cupcakes`, usando nomes de serviço (`db`, `mailpit`) em vez de `localhost`.

## Comandos úteis

```bash
# Rodar migrations manualmente
docker compose exec app php artisan migrate

# Repopular o banco do zero
docker compose exec app php artisan migrate:fresh --seed

# Acessar o Tinker
docker compose exec app php artisan tinker

# Ver rotas registradas
docker compose exec app php artisan route:list

# Logs de um serviço
docker compose logs -f app
```

## Funcionalidades

**Cliente**: cadastro/login/recuperação de senha, catálogo com busca e filtro por categoria, página de produto com avaliações e comentários, carrinho persistido em banco, checkout com endereço/pagamento/cupom/frete, confirmação de pedido por e-mail, acompanhamento e cancelamento de pedidos, avaliação de produtos comprados.

**Admin**: dashboard financeiro e de tendências, CRUD de produtos e categorias (com exclusão lógica), gestão de estoque, gestão de pedidos com transições de status, histórico de vendas com filtros, gestão de cupons, moderação de comentários.

## Observações de escopo

- Não há testes automatizados, gateway de pagamento real ou integração real de frete — conforme especificado no escopo do projeto.
- O faturamento exibido no dashboard considera apenas pedidos com status **entregue**.
- Autorização de admin é feita por checagem explícita de papel (`role`) via middleware dedicado, nunca apenas por rota ou autenticação.
