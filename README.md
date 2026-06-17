# JWT Login Laravel - Demonstração de Autenticação

Este é um projeto de demonstração desenvolvido em Laravel para ilustrar a implementação de rotas de autenticação. Ele serve como um guia prático para configurar o fluxo de login, registro e proteção de rotas.

## Descrição do Projeto

O projeto foca na implementação de uma API ou sistema web que utiliza tokens (frequentemente JWT) para autenticar usuários. Ele contém exemplos de:
- Registro de novos usuários.
- Login de usuários existentes.
- Proteção de rotas através de Middlewares de autenticação.
- Logout e invalidação de sessão/token.

## Pré-requisitos

Antes de começar, você precisará ter instalado em sua máquina:
- [PHP >= 8.3](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Node.js & NPM](https://nodejs.org/)
- Um banco de dados (SQLite, MySQL, ou PostgreSQL)

## Como fazer o projeto funcionar

Siga os passos abaixo para rodar o projeto localmente:

1. **Clonar o repositório:**
   ```bash
   git clone https://github.com/MucaBecker/JWT-login-laravel.git
   cd JWT-login-laravel
   ```

2. **Instalar as dependências do PHP:**
   ```bash
   composer install
   ```

3. **Instalar as dependências do Frontend (Vite/Tailwind):**
   ```bash
   npm install
   npm run build
   ```

4. **Configurar o ambiente:**
   Copie o arquivo de exemplo e gere a chave da aplicação:
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan jwt:secret
   ```
   *Nota: Não esqueça de configurar a conexão com o banco de dados no arquivo `.env`.*

5. **Rodar as migrações do banco de dados:**
   ```bash
   php artisan migrate
   ```

6. **Iniciar o servidor de desenvolvimento:**
   ```bash
   php artisan serve
   ```
   O projeto estará disponível em `http://127.0.0.1:8000`.
