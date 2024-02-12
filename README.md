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

#### Clonar esse repositório

### Criar arquivo .env com as seguintes propriedades, que estão também no .env.example:

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

- AUTHORIZATION_SERVICE=
- NOTIFICATION_SERVICE=

### Step 2: Subir aplicação

```bash
docker-compose up -d --build
```

### Step 3: Rodar Migrations

```bash
docker exec -it transactions-api php bin/hyperf.php migrate
```

## ❯ Execução de testes
```bash
make tests
```

## ❯ Command para executar rotina de envio de notificações
```bash
make notify
```

## ❯ Endpoints

| Route                | Description                                                        |
| -------------------- | ------------------------------------------------------------------ |
| **/api/v1/users**    | POST - Cadastra um novo usuário, junto com sua carteira            |
| **/api/v1/users/id** | GET - Busca um usuário pelo seu Uuid                               |
| **/api/v1/users/id/deposit** | POST - Deposita um valor na carteira do usuário            |
| **/api/v1/transactions** | POST - Realiza uma transação entre dois usuários               |

## ❯ Modelo de domínio

![Texto Alternativo](https://cdn.discordapp.com/attachments/454717769892626455/1206508854096039986/image.png?ex=65dc43ee&is=65c9ceee&hm=ca4b7f4602196c3fc29bba96d3646ac9b4e01a67d431fbe2f561e6edf3d462da&)

## ❯ Modelo de banco de dados

![Texto Alternativo](https://cdn.discordapp.com/attachments/454717769892626455/1206500749316595772/image.png?ex=65dc3c62&is=65c9c762&hm=a928351f83909fed6fd469307f1e6cbd9685b4ed9b35f2c029c90799bf531fb9&)