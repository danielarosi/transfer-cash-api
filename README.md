
## API de Transação Financeira
Projeto back-end desenvolvido no Framework Laravel 9 com PHP 8, para API de movimentações financeiras (transferências) entre usuários.

### Configurações
Clone o repositório para criar uma cópia local no seu computador, cd "diretorio de sua escolha"
``` bash
git clone https://github.com/danielarosi/transfer-cash-api
```


Copie o arquivo ".env.example" e o renomei para ".env"
```sh
cd example-project/
cp .env.example .env
```
Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME=Laravel
APP_ENV=local
APP_KEY=COLAR_AQUI_KEY_GERADA
APP_DEBUG=true
APP_URL=http://localhost:8989

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=cashflow
DB_USERNAME=root
DB_PASSWORD=root
```
Suba os containers do projeto
```sh
docker-compose up -d --build
```
Para acessar o container
```sh
docker-compose exec app bash
```
Instale as dependências do projeto
```sh
composer install
```
Gere a key do projeto Laravel, copie e cole na variável de ambiente APP_KEY do arquivo ".env"
```sh
php artisan key:generate
```
Execute as migrations para gerar o banco de dados
```sh
php artisan migrate
```
Execute os Seeders para popular as tabelas
```sh
php artisan db:seed --class=UserTypeSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=AccountSeeder
```

Para executar os testes 
```sh
php artisan test 
```
Para executar apenas um teste unitário de um método da Class
```sh
php artisan test --filter <NAME_TEST_METHOD> 
```

### Modelo de Dados

<img src="/docs/screenshot-1.png" alt="Diagrama Modelo de Dados"/>
    
### Arquitetura Lógica
    
<img src="/docs/screenshot-2.png" alt="Diagrama Arquitetura Lógica"/>
    
### Modelo de Componentes
    
<img src="/docs/screenshot-3.png" alt="Diagrama Modelo de Componentes"/>
    
## Métodos
As API's desenvolvidas neste projetos adotam o seguinte padrão:
| Método | Descrição |
|---|---|
| `POST` | Método http para criar um novo registro. |
| `GET` | Método http para recuperar um ou mais registros. |

## Respostas
| Código | Descrição |
|---|---|
| `200` | Requisição GET executada com sucesso.|
| `201` | Registro criado com sucesso.|
| `400` | Erros de validação feitos na camada rest api, por exemplo campos obrigatórios.|
| `404` | Registro não encontrado.|
| `422` | Erros persistência, possivelmente por restrições de regras em campos.|

### Para execução da API foi utilizado Postman com as seguintes rotas:

### Usuários
Criar usuário [o parâmetro "user_types_id" pode ser 1 (Usuário Comum) ou 2 (Lojista)]
```sh
POST/api/users/
```
```sh
Request:
   {
        "fullname": "João da Silva",
        "cpf_cnpj": "123.456.789-01",
        "email": "joao@gmail.com",
        "password": "12345678",
        "user_types_id": 1,
        "initial_balance": "1000"
    },
```

```sh
Response:
{
    "success": true,
    "response": {
        "code": 201,
        "message": "Usuário criado.",
        "data": {
            "id": 1,
            "fullname": "João da Silva",
            "cpf_cnpj": "123.456.789-01",
            "email": "joao@gmail.com",
            "user_types_id": "1",
            "account_id": 1,
            "balance": "1000.0",
            "created_at": "2022-05-10T22:34:55",
            "updated_at": "2022-05-10T22:34:55"
        }
    }
}
```
Listar todos usuários
```sh
GET/api/users
```
```sh
Request: {}
```
```sh
Response:
{
    "success": true,
    "response": {
        "code": 200,
        "data": [
            {
                "id": 1,
                "fullname": "João da Silva",
                "cpf_cnpj": "326.457.013-02",
                "email": "joao@gmail.com",
                "user_types_id": 1,
                "created_at": "2022-05-09 21:59:51",
                "account_id": 1,
                "balance": "750.80"
            },
            ...
        ]
    }
}

```
Buscar usuário por ID

```sh
GET/api/users/{id}
```
```sh
Request:
{}
```

```sh
Response:
{
    "success": true,
    "response": {
        "code": 200,
        "data": {
            "id": 2,
            "fullname": "Maria da Silva",
            "cpf_cnpj": "103.600.162-86",
            "email": "maria.silva@gmail.com",
            "user_types_id": 1,
            "created_at": "2022-05-09 21:59:51",
            "account_id": "2",
            "balance": "1500.00"
        }
    }
}
```
### Transações
Transação de valores entre usuários
```sh
POST/api/transactions
```
```sh
Request:
{
    "value" : 100.00,
    "payer" : 3,
    "payee" : 7
}
```
```sh
Response:
{
    "success": true,
    "response": {
        "code": 201,
        "message": "Transferêcia realizada."
    }
}
```

Listar todas as transações:
```sh
GET/api/transactions
```
```sh
Request: {}
```
```sh
Response:
[
    {
        "id": 1,
        "initial_balance_source": "1050.80",
        "initial_balance_target": "766.80",
        "transferred_value": "100.00",
        "created_at": "2022-05-09T22:13:07.000000Z",
        "updated_at": "2022-05-09T22:13:07.000000Z",
        "source_id": 1,
        "target_id": 9
    },
    ...
]
```
Listar transação por ID do Pagador/ Payer
```sh
GET/api/transactions/{source_id}/{id}
```
```sh
Request: {}
```
```sh
Response:
{
    "success": true,
    "response": {
        "code": 200,
        "data": [
            {
                "id": 1,
                "initial_balance_payer": "1050.80",
                "initial_balance_payee": "766.80",
                "transferred_value": "100.00",
                "payer_id": 1,
                "payee_id": 9,
                "created_at": "2022-05-09T22:13:07.000000Z",
                "updated_at": "2022-05-09T22:13:07.000000Z"
            },
            ...
        ]
    }
}
```

