# clubs-dataclick
Para a atividade, foi feita uma API Rest em Laravel 5.2 e um app em Angular que consulta essa API.

Tentei usar o mínimo possível de libs extenas, tanto na API quanto no app Web: só usei uma para configurar o Cors no Laravel.

Para atualizar recursos, a API só está aceitando o PATCH no header. Pode dar erro em navegadores mais antigos ou desatualizados.

### API Deploy:
* Instalar as dependências:
```
composer install
```
* Configurar as variáveis de banco no .env do Laravel:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=database
DB_USERNAME=user
DB_PASSWORD=pass
```
* Migrar o banco:
```
php artisan migrate
```
* Rodar o server:
```
php artisan serve
```

### WEB deploy
A constante `API_ENDPOINT` do `app/app.config.js` deve estar apontando para o serviço em execução do Laravel.

Para executar, abrir o `index.html` ou através do NodeJs.

Pelo NodeJs:
* Instalar as dependências:
```
npm install
```
* Rodar o server: 
```
node server.js
```

## Testes
Foi feito testes, somente, na API. Não foi usado nenhuma lib externa, foi usado o PHPUnit que vem o Laravel.

Para executar: `vendor/phpunit/phpunit/phpunit`

## Demo
O demo está publicado no Heroku:

http://www.dataclick.lourenci.com