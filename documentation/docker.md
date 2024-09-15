# Shaark - Docker

Docker can be used to deploy Shaark. By default it will install Shaark in production mode.  
This behaviour can be changed by editing the Dockerfile.  

## Clone Project
```bash
$ git clone https://github.com/MarceauKa/shaark
```

## Build Docker-Image
```bash
$ docker build -t shaark:2.0.0_alpha .
```
run command in Project root

## Docker-compose Example
```yml
version: "3.0"

services:
    # mysql database instance for development purposes
    shaark-db:
        container_name: shaark-db
        image: mysql:8
        restart: "no"
        environment:
            MYSQL_ROOT_USER: "root"
            MYSQL_RANDOM_ROOT_PASSWORD: "true"
        volumes:
            # contains script for creating shaark database and user (must be self created)
            - ./initdb.d:/docker-entrypoint-initdb.d

    shaark-laravel:
        container_name: shaark-laravel
        image: shaark:2.0.0_alpha
        restart: "no"
        ports:
            # webserver
            - "8080:80"
        depends_on:
            - "shaark-db"
        volumes:
            - ./.env:/var/www/laravel/.env
            - ./storage:/var/www/laravel/storage

```

## Setup

- Create the SQL scripts to create the database and the user in the './initdb.d' folder.
- Create the docker-compose.yml
- create your .env file with valid data (.env.example)
  - database host is 'shaark-db'
  - database port is 3306
- execute the following commands:
  ```bash
    $ docker-compose up -d
    $ docker-compose exec -it shaark-laravel /usr/bin/php artisan key:generate
    $ docker-compose exec -it shaark-laravel /usr/bin/php artisan storage:link
    $ docker-compose exec -it shaark-laravel /usr/bin/php artisan install
  ```

now you can reach the site fully functional under port 80 on your server
