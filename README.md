### Desafio: Crud de Produtos + Variações

Segue abaixo instruções de instalação para rodar o projeto.


#### Instalando Ambiente Docker

criar e subir os containers dos serviços necessários para a aplicação

>  docker-compose up -d

#### Instalando Laravel dentro do container PHP
acesse o container PHP
> docker-compose exec php-fpm bash

Instale as dependências do  Laravel
> composer install

configure a chave do projeto
> php artisan key:generate

#### configurar variáveis de ambiente
crie o arquivo ".env" e configure a conexão com o banco de dados etc.
modifique as variáveis abaixo:

    APP_URL=http://localhost:8000 #url do projeto
    
    DB_CONNECTION=mysql
    DB_HOST=mysql #nome do container ou url + porta do banco de dados
    DB_PORT=3306
    DB_DATABASE=crud_produtos
    DB_USERNAME=dbuser
    DB_PASSWORD=10203040
	
	CACHE_DRIVER=redis
	QUEUE_CONNECTION=redis
	SESSION_DRIVER=redis
	
	REDIS_HOST=redis
dê as permissões corretas para as pastas:
chown -R www-data:www-data /application/storage /application/bootstrap/cache chmod -R 775 /application/storage /application/bootstrap/cache

entre no container php:
> docker-compose exec php-fpm bash

e rode:
> php artisan optimize
> php artisan migrate

agora acesse:
> localhost:8000