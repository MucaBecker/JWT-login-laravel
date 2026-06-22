# JWT Login Laravel - Demonstração de Autenticação

Este é um projeto de demonstração desenvolvido em Laravel para ilustrar a implementação de rotas de autenticação utilizando JWT (JSON Web Tokens). Ele serve como um guia prático para configurar o fluxo de login, registro de usuários e proteção de rotas de API.

## Descrição do Projeto

O projeto foca na implementação de uma API RESTful protegida por autenticação JWT. A arquitetura é centralizada no `UserController`, suportado por Services (`AuthService`) e Form Requests para validação.

### Rotas e Funcionalidades Implementadas

As rotas da API estão definidas em `routes/api.php` e incluem:

- **POST /api/user**: Rota de registro de novos usuários. Protegida por rate limiting (5 requisições por minuto).
- **POST /api/login**: Rota de autenticação. Recebe credenciais e retorna um token JWT de acesso. Também protegida por rate limiting (5 requisições por minuto).
- **GET /api/user/{id}**: Rota protegida. Retorna os detalhes de um usuário específico. Acesso restrito via middleware `auth.jwt`, exigindo um token JWT válido.

*Observação: O projeto foca no núcleo de autenticação e registro, portanto funcionalidades como logout não estão expostas nas rotas atuais.*

### Estrutura e Controladores

- **UserController**: Controlador principal que gerencia as requisições de login, criação de usuário e obtenção de dados. 
- **Documentação de API**: O projeto utiliza atributos OpenAPI/Swagger nativos no `UserController` para gerar documentação interativa das rotas, esquemas de requests e respostas.

## Pré-requisitos

Antes de começar, você precisará ter instalado em sua máquina:
- PHP >= 8.3
- Composer
- Node.js & NPM
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
   Copie o arquivo de exemplo e gere a chave da aplicação e o segredo do JWT:
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

## Como rodar os testes

O projeto possui uma suíte de testes (Testes de Unidade e Testes de Funcionalidade) para garantir o funcionamento correto das rotas e serviços.

Para executar todos os testes, utilize o comando:

```bash
php artisan test
```

Alternativamente, você pode usar o PHPUnit diretamente:

```bash
./vendor/bin/phpunit
```
