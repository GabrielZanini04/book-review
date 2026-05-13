# Book Review

Um aplicativo web simples para gerenciar livros e publicar resenhas, desenvolvido com o framework Laravel.

## Sobre o projeto

Este projeto permite visualizar uma lista de livros, consultar detalhes individuais e publicar resenhas com nota de 1 a 5. O foco está em:

- Exibir livros e resenhas relacionadas
- Buscar livros por título
- Filtrar livros por popularidade e melhor avaliação
- Salvar resenhas com validação e limite de envio
- Usar cache para acelerar consultas de livros

## Tecnologias utilizadas

- PHP 8.2
- Laravel 12
- Laravel Eloquent ORM
- Vite
- Tailwind CSS
- Axios
- PHPUnit
- Laravel Pail
- Laravel Pint

## Estrutura do projeto

- `app/Models`
  - `Book.php` — modelo principal com relacionamentos e scopes de consultas personalizadas
  - `Review.php` — modelo de resenha com validação de criação e cache invalidation
  - `User.php` — modelo padrão de usuário Laravel

- `app/Http/Controllers`
  - `BookController.php` — lista e exibe livros, aplica filtros e armazena resultados em cache
  - `ReviewController.php` — cria e armazena resenhas com throttle e validação

- `routes/web.php` — rotas de recurso para `books` e `books.reviews`

- `database/migrations`
  - `create_books_table.php` — tabela de livros com `title`, `author` e timestamps
  - `create_reviews_table.php` — tabela de resenhas com `review`, `rating` e `book_id`

- `resources/views` — views Blade para apresentação de lista de livros, detalhes e formulário de review

## Técnicas aplicadas

- `Route::resource` para rotas RESTful
- Escopos Eloquent (`scopePopular`, `scopeHighestRated`, `scopeTitle`, etc.) para lógica de consulta reutilizável
- Cache com `cache()->remember()` em listagem e exibição de livros
- Relacionamento `hasMany` / `belongsTo` entre `Book` e `Review`
- Validação de formulário no `ReviewController` com regras de `required`, `min`, `max` e `integer`
- Middleware de throttle para evitar envio excessivo de resenhas
- Invalidation de cache automático via eventos de modelo (`booted`)

## Como rodar o projeto

### Requisitos

- PHP 8.2+
- Composer
- Node.js / npm
- Banco de dados suportado pelo Laravel (por exemplo, SQLite, MySQL)

### Passos

1. Instalar dependências PHP:

```bash
composer install
```

2. Copiar o arquivo de ambiente e gerar chave:

```bash
copy .env.example .env
php artisan key:generate
```

3. Configurar o banco de dados em `.env`

4. Rodar migrations:

```bash
php artisan migrate
```

5. Instalar dependências JavaScript:

```bash
npm install
```

6. Compilar ativos:

```bash
npm run build
```

7. Iniciar o servidor de desenvolvimento:

```bash
php artisan serve
```

8. Acessar no navegador:

```
http://127.0.0.1:8000
```

## Comandos úteis

- `php artisan test` — executar testes
- `npm run dev` — iniciar o Vite em modo de desenvolvimento
- `npm run build` — compilar assets para produção

## Observações

O projeto está desenhado para ser uma aplicação de demonstração de reviews de livros, com foco em boas práticas do Laravel, organização de código e performance simples por meio de cache e query scopes.
