# Code for Laravel RBAC Tutorial

This repository holds the code for the [Laravel RBAC tutorial](https://blog.shahednasser.com/implementing-rbac-in-laravel-tutorial/).

## Installation

After cloning the repository, install the dependencies with Composer:

```bash
composer install
```

## Change Database Configuration

Change the database configuration in `.env` with your configurations:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

## Migrate Database

Migrate database:

```bash
php artisan migrate
```

## Start the Server

Start the server with this command:

```bash
php artisan serve
```
