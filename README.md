# PHPay API 🚀

[![Versão](https://img.shields.io/badge/Vers%C3%A3o-1.0.0-blue.svg)](https://github.com/andrewrdev/phpay-api/releases)
[![Licença](https://img.shields.io/badge/Licen%C3%A7a-GPL%20v3.0-green.svg)](LICENSE.md)
[![Build Status](https://img.shields.io/badge/Build-Ok-brightgreen.svg)](https://github.com/andrewrdev/phpay-api/actions)


## Introdução

Bem-vindo ao PHPlay API, uma solução em PHP para facilitar transferências de dinheiro entre usuários comuns e lojistas. Esta API oferece funcionalidades essenciais para um sistema financeiro simples e eficiente.

## Funcionalidades Principais 🛠️

1. **Cadastro de Usuário:**
   - Endpoint: `POST /api/v1/users`
   - Permite o registro de usuários com informações como nome completo, CPF, e-mail e senha. Garante unicidade de CPF/CNPJ e e-mails no sistema.

2. **Transferência de Dinheiro:**
   - Endpoint: `POST /api/v1/transactions`
   - Facilita a transferência de dinheiro entre usuários e lojistas. Valida o saldo do usuário antes da transferência e utiliza um serviço externo para autorização.

## Como Utilizar 🚦

Para utilizar o PHPlay API, basta seguir os seguintes passos:

1. Clone este repositório:
   ```bash
   git clone https://github.com/andrewrdev/phpay-api.git
   ```

2. Navegue até o diretório do projeto:
   ```bash
   cd phpay-api
   ```

3. Configure o ambiente com o xampp, laragon, docker ..., defina a pasta public como Document Root: 
   ```bash
   DocumentRoot "C:/laragon/www/phpay-api/public"
   ```  

4. Execute no seu mysql o script do database.sql na pasta src, irá criar o banco de dados e as tabelas necessárias.

5. Configure o application.properties na pasta src:
    ```bash
    DATABASE_HOST='localhost'
    DATABASE_NAME='api_phpay'
    DATABASE_USER='root'
    DATABASE_PASSWORD=''
    ```

6. Acesse os endpoints do sistema via navegador ou utilize ferramentas como [Postman](https://www.postman.com/)

## Endpoints 📚

   Listagem de Usuários:
   ```bash
      GET /api/v1/users
   ```

   Detalhes de um Usuário:
   ```bash
      GET /api/v1/users/{id}
   ```

   Inserção de Usuário:
   ```bash
      POST /api/v1/users
   ```

   Atualização de Usuário:
   ```bash
      PUT /api/v1/users/{id}
   ```

   Exclusão de Usuário:
   ```bash
      DELETE /api/v1/users/{id}
   ```

   Deposito de Usuário:
   ```bash
      POST /api/v1/users/deposit
   ```

   Listagem de Transações:
   ```bash
      GET /api/v1/transactions
   ```

   Detalhes de uma Transação:
   ```bash
      GET /api/v1/transactions/{id}
   ```

   Inserção de Transação:
   ```bash
      POST /api/v1/transactions
   ```

   Exclusão de Transação:
   ```bash
      DELETE /api/v1/transactions/{id}
   ```

   Listagem de Notificações:
   ```bash
      GET /api/v1/notifications
   ```

   Detalhes de uma Notificação:
   ```bash
      GET /api/v1/notifications/{id}
   ```

   Exclusão de Notificação:
   ```bash
      DELETE /api/v1/notifications/{id}
   ```

## Contribuições e Feedback 🤝

Contribuições são bem-vindas! Se encontrar problemas, tenha ideias ou queira melhorar algo, sinta-se à vontade para abrir uma [issue](https://github.com/andrewrdev/phpay-api/issues).

## Licença 📜

Este projeto é licenciado sob a [GNU General Public License v3.0](LICENSE.md) - veja o arquivo LICENSE.md para detalhes.