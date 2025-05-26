
# 🗒️ Projeto de Anotações Pessoais com Laravel + Docker

Este projeto é um sistema simples de gerenciamento de anotações pessoais com autenticação de usuários, usando **Laravel 10**, **Docker**, **Nginx**, **MySQL**, **Blade** e **Bootstrap**.

---

## ✅ Tecnologias Utilizadas

- PHP 8.2 (via Docker)
- Laravel 10
- MySQL 8
- Nginx
- Bootstrap 5
- Docker + Docker Compose

---

## ⚙️ Pré-requisitos

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- Git (opcional)

---

## 🚀 Instalação e Configuração

### 1. Clone o repositório

```bash
git clone https://github.com/nathanjsilva/gerenciamento-notas.git
cd seu-repo
```

### 2. Estrutura de pastas

Certifique-se de ter a seguinte estrutura:

```
project-root/
│
├── docker/
│   └── nginx/
│       └── default.conf
│
├── laravel/           ← Aplicação Laravel será instalada aqui
├── Dockerfile
├── docker-compose.yml
└── README.md
```

### 3. Configure os arquivos do Docker

#### `Dockerfile`

```Dockerfile
FROM php:8.2-fpm

# Instala extensões e dependências
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    nano \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["php-fpm"]
```

#### `docker-compose.yml`

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: laravel_app
    working_dir: /var/www
    volumes:
      - ./laravel:/var/www
    networks:
      - gerenciamento-notas_app-network
    depends_on:
      - mysql

  nginx:
    image: nginx:latest
    container_name: nginx_server
    ports:
      - "8080:80"
    volumes:
      - ./laravel:/var/www
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - gerenciamento-notas_app-network
    depends_on:
      - app

  mysql:
    image: mysql:8.0
    container_name: mysql_db
    environment:
      MYSQL_ROOT_PASSWORD: "root123"
      MYSQL_DATABASE: gerenciamento_notas
      MYSQL_USER: laravel
      MYSQL_PASSWORD: "1234"
    ports:
      - "3306:3306"
    volumes:
      - dbdata:/var/lib/mysql
      - ./init.sql:/docker-entrypoint-initdb.d/init.sql
    networks:
      - gerenciamento-notas_app-network

volumes:
  dbdata:

networks:
  gerenciamento-notas_app-network:
    driver: bridge
```

#### `default.conf` (Nginx)

```nginx
server {
    listen 80;
    index index.php index.html;
    server_name localhost;
    root /var/www/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 4. Suba os containers

```bash
docker-compose up -d --build
```

### 5. Instale dependências e gere chave do Laravel

```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
```

### 6. Configure permissões

```bash
sudo chmod -R 777 laravel/storage laravel/bootstrap/cache
```

Ou:

```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### 7. Acesse a aplicação

Abra no navegador:

```
http://localhost:8080
```

## 📁 Configuração do `.env`

Crie um `.env` baseado no `.env.example` e edite as configs de banco de dados:

```
DB_CONNECTION=mysql
DB_HOST=mysql_db
DB_PORT=3306
DB_DATABASE=gerenciamento_notas
DB_USERNAME=laravel
DB_PASSWORD=1234
```

## 🧪 Comandos úteis

```bash
docker-compose exec app bash           # Acessar container app
docker-compose exec app php artisan migrate
docker-compose exec app php artisan serve
```

## 💡 Problemas comuns

### ❌ Erro de permissão no `laravel.log`

> `Failed to open stream: Permission denied`

✔️ Rodar:

```bash
sudo chmod -R 777 laravel/storage
```

## ✍️ Autor

Desenvolvido por **Nathan de Jesus Silva** para teste técnico de Laravel + Docker.
