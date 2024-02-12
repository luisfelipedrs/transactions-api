## ❯ Projeto

Desenvolvimento de API Rest para uma aplicação de transações monetárias com endpoints para realizar transações, cadastrar usuários e depositar valores em suas carteiras.

### Tecnologias utilizadas

- PHP
- Hyperf Framework
- MySQL
- Docker
- Swagger OpenApi
- Testes com PHPUnit

## ❯ Começando

### Step 1: Configurar ambiente

## Clonar esse repositório

## Criar arquivo .env com as seguintes propriedades, que estão também no .env.example:

- APP_NAME=
- APP_ENV=

- DB_DRIVER=
- DB_HOST=
- DB_PORT=
- DB_DATABASE=
- DB_USERNAME=
- DB_PASSWORD=
- DB_CHARSET=
- DB_COLLATION=
- DB_PREFIX=

- REDIS_HOST=
- REDIS_AUTH=
- REDIS_PORT=
- REDIS_DB=

- AUTHORIZATION_SERVICE=
- NOTIFICATION_SERVICE=

### Step 2: Subir aplicação

```bash
docker-compose up -d --build
```

## ❯ Endpoints

| Route                | Description                                                        |
| -------------------- | ------------------------------------------------------------------ |
| **/api/v1/users**    | POST - Cadastra um novo usuário, junto com sua carteira            |
| **/api/v1/users/id** | GET - Busca um usuário pelo seu Uuid                               |
| **/api/v1/users/id/deposit** | POST - Deposita um valor na carteira do usuário            |
| **/api/v1/transactions** | POST - Realiza uma transação entre dois usuários               |