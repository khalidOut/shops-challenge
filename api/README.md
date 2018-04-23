# Shops - Api

This api was created using [Symfony 4](https://github.com/symfony/symfony/tree/4.0), [Api Platform](https://github.com/api-platform/api-platform) and [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md)

## Requirement

Php 7.1.3 or higher.

MariaDB 10.1.9 or Mysql equivalent.

Composer. [Get latest version](https://getcomposer.org/download/).

## Install

Clone the projet.

Run `cd api`.

Run `$ composer install` or `$ php composer.phar install`.

You can access directly or configure a virtal host that point to `public`

## LexikJWTAuthenticationBundle Configuration

Run `$ mkdir config/jwt`.

Run `$ openssl genrsa -out config/jwt/private.pem -aes256 4096`.

The prompt will ask for a passphrase to continue, copy `JWT_PASSPHRASE` phrase from `.env` and insert it.

Run `$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem`.

The prompt will ask for a passphrase to continue, enter the same phrase as the first command.

PS: for windows, you can use powerShell to run this commands (Ex : `powershell -Command "(C:\xampp\apache\bin\openssl.exe genrsa -out C:\xampp\htdocs\shops-challenge\api\config\jwt\private.pem -aes256 4096)"`);

## Database Configuration

Go to .env file to configure database url. Default `DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name`.

Run `php bin/console doctrine:database:create` to create the database.

Run `php bin/console doctrine:schema:update --force` to create database schema.

PS: If your database server dose not support JSON Type, change `$roles` property type to `@ORM\Column(type="text")` in `src/Entity/User.php`, execute the command and return it back to `@ORM\Column(type="json")`.
Import the shops data from `var/data/shop.sql`. (Ex: `$ mysql -u db_user -p db_name < var/data/shop.sql`)

## Cross-Allow-Origin

You can set the allowed origin to use this api in `.env`. Default configuration `CORS_ALLOW_ORIGIN=^https?://localhost:?[0-9]*$`.

## Further help

For more help feel free to contact me at `khalid.outznit@gmail.com` or open a new issue.
