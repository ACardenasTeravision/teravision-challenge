Shorten URL - Full Stack Challenge

Simple app to shorten urls.

REQUIREMENTS

Laravel 5.8
MySQL
Apache or Nginx server (to avoid conflicts in localhost with Guzzle library)
Composer

VERSION

1.0.0

INSTALLATION

Clone the repository in the project's folder of your web server.
Create a virtualhost.
Create an empty database.
Move to the project's root folder.
Install dependencies with
  composer install
If you don't have .env file copy the .env.example and rename it.
  cp .env.example .env
Add database name and app url to the .env configuration.
Run php artisan key:generate to generate the app key.
Run php artisan migrate to create the database tables.

FUTURE IMPROVEMENTS
- Add comments to the code
- User a JS Framework to handle the front-end side
- Create tests
