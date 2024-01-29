#Tunis-RH-api
Tunis-RH-api used for [Tunis-RH-react](https://gitlab.cosavostra.com/pfes/tunis-rh/tunis-rh-react) project.
## Requirements
From [Symfony setup page](https://symfony.com/doc/current/setup.html):
> This project needs [PHP 8.1](https://www.php.net/releases/8.1/en.php) (due to API [Platform 2.6](https://api-platform.com/docs/distribution/)), and these PHP extensions (which are installed and enabled by default by PHP):
> - [Ctype](https://www.php.net/book.ctype),
> - [iconv](https://www.php.net/book.iconv),
> - [JSON](https://www.php.net/book.json),
> - [PCRE](https://www.php.net/book.pcre),
> - [Session](https://www.php.net/book.session),
> - [SimpleXML](https://www.php.net/book.simplexml),
> - [Tokenizer](https://www.php.net/book.tokenizer)

to check if your computer meets all requirements. Open your console terminal and run this command:
```bash
symfony check:requirements
```
## Git information
This project works with one protected branch:
- The `main` branch, which is used for finished and merged branchs
## Installation
First of all, clone the project:
```bash
git clone https://github.com/chbinouz/movies-api.git
```
### env
- Copy default env file:
```bash
cp .env .env.local
```
- Open `.env.local` file and replace `user`, `pass` and `db_name` from:
```md
DATABASE_URL=mysql://user:pass@127.0.0.1:3306/db_name
```
with your database information
- Define the `JWT_PASSPHRASE` environment key,
- set the "Parameters" environment keys
### Install dependencies:
```bash
php composer install --no-scripts
```
### Database:
```bash
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:schema:update --dump-sql --force
```
### To add Entity:
```bash
php bin/console make:entity <entity name>
```
### To Migrate:
```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
####PS:
if you already installed the [Symfony CLI](https://symfony.com/download)
you cane use:
```bash
symfony console <your command>
```
For example:
```bash
symfony console make:entity <entity name>
```
or
```bash
symfony console make:migration
```
### JWT configuration
*Prerequisites*: make sure you have `openssl` installed on your local machine, [if not](https://www.openssl.org/source/):
 ```bash
openssl version
 ```
Generate the JWT keys:
```bash
php bin/console lexik:jwt:generate-keypair
```
Your keys will land in config/jwt/private.pem and config/jwt/public.pem (unless you configured a different path).

Available options:

--skip-if-exists will silently do nothing if keys already exist.
--overwrite will overwrite your keys if they already exist.
Otherwise, an error will be raised to prevent you from overwriting your keys accidentally.

## Running project:

- Go to [Symfony CLI](https://symfony.com/download) page and follow instruction for your OS
- Then, run:
```bash
symfony server:start
```

## To add Admin User
- Create User in subscription page `/sinup`
- Go to the database and change the role of the user in the `User` table to `["ROLE_ADMIN"]`