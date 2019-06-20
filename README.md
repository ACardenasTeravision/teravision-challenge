# Shorten URL - Full Stack Challenge

## Simple app to shorten urls.

---

### REQUIREMENTS

* Laravel 5.8
* MySQL
* Apache or Nginx server (to avoid conflicts in localhost with Guzzle library)
* Composer

---

### INSTALLATION

1. Clone the repository in the project's folder of your web server.
2. Create a virtualhost.
3. Create an empty database.
4. Move to the project's root folder.
5. Install dependencies with `composer install`
6. If you don't have .env file copy the .env.example and rename it using `cp .env.example .env`
7. Add database name and app url to the .env configuration.
8. Run `php artisan key:generate` to generate the app key.
9. Run `php artisan migrate` to create the database tables.

---

### CHALLENGES
- Understand how shorten urls works. Was necessary to read about tools like Bitly and TinyURL, the advantages that it gives and why is these useful.
- Use Guzzle. Know how to make http request to an internal api from Laravel. Read a lot of forum posts to figure out how to make Guzzle works in a local environment. Guzzle have troubles if you use the php artisan serve command to create a local server.
- Use Goutte. Know how to use this library to extract information of a page. Figure out how to crawl element in the page to get the title.
---

### REASONING
- Database: To store the shortened urls was necessary to create a database table with the right fields: the original url, a code of 6 random characters mixed with numbers, the shortened url, the title of the page and the number of times this shortened link is visited.

- Web routes:
  - '/': Main page in which the api call 'api/get-top-urls' is used to get the top 100 most frequently visited urls ordered from the most to the less visited.
  - 'shorten': Used to call the api route '/api/get-shorten-url/' to have the shortened url to store a new register in the database if the url inserted was not shortened before.
  - '{code}': Use to call the api route '/api/get-link/' if a shorten url is clicked, it increments the times_visited field and redirects the user to the original url.

- API routes:
  - 'get-top-urls': Returns the top 100 most frequently visited urls.
  - 'get-shorten-url/{url}' Requires the original url and returns the shortened version
  - 'get-link/{code}' Requires the random code of the shortened url and return the link to the original url

---

### FUTURE IMPROVEMENTS
- Add comments to the code
- Check all the validations
- User a JS Framework to handle the front-end side
- Create tests
