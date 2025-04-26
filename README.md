<p align="center">
  <img src="/public/images/logo.png" width="300" alt="Adote um pet Logo">
</p>

# Adote um Pet

**Adote um Pet** é uma plataforma completa para adoção de animais, conectando ONGs, protetores e adotantes. O sistema possui API RESTful, painel web e integração em tempo real com app mobile (Flutter).

## Funcionalidades

- Cadastro e autenticação de usuários (incluindo social login)
- Cadastro, listagem, filtro e busca de pets para adoção
- Upload de fotos dos pets
- Sistema de chat em tempo real (WebSocket via Laravel Reverb)
- Cadastro e gerenciamento de ONGs/parceiros
- Painel administrativo (web)
- API documentada com Swagger/OpenAPI
- Integração com app Flutter

## Tecnologias

- **Backend:** Laravel 11+, PHP 8.2+
- **WebSocket:** Laravel Reverb
- **API Auth:** Laravel Sanctum
- **Banco de Dados:** MySQL ou PostgreSQL
- **Frontend:** Blade + Tailwind + DaisyUI (web)
- **Mobile:** Flutter (consome a API)
- **Documentação:** OpenAPI/Swagger

## Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/tecrodrigocastro/adote_um_pet.git
   cd adote_um_pet
   ```

2. Instale as dependências:
   ```bash
   composer install
   ```

3. Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente (DB, Reverb, etc):
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Execute as migrations:
   ```bash
   php artisan migrate
   ```

5. (Opcional) Popule o banco com dados fake:
   ```bash
   php artisan db:seed
   ```

6. Inicie o servidor Laravel:
   ```bash
   php artisan serve
   ```

7. Inicie o servidor WebSocket (Reverb):
   ```bash
   php artisan reverb:start
   ```

## Como testar o WebSocket

- Certifique-se de que o servidor Reverb está rodando (`php artisan reverb:start`)
- Use o app Flutter ou Laravel Echo (web) para se conectar ao canal privado do chat
- Mensagens enviadas via API aparecerão em tempo real para todos conectados ao mesmo chat

## Documentação da API

A documentação dos endpoints está disponível via Swagger/OpenAPI nas anotações dos controllers.

## Estrutura de Diretórios

- `app/Http/Controllers/Chat/ChatController.php` — Gerencia os chats
- `app/Http/Controllers/Message/MessageController.php` — Gerencia as mensagens
- `app/Events/MessageSent.php` — Evento broadcast de mensagem
- `resources/views/welcome.blade.php` — Landing page web
- `config/reverb.php` — Configuração do WebSocket

## Contribuição

Contribuições são bem-vindas! Abra uma issue ou envie um pull request.

## Licença

MIT

---
