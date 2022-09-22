# Getting Started

## PHP Setup

Install PHP 7.4 or above. Follow the steps for your OS in link (<https://www.php.net/manual/en/install.php>).

## Composer Setup

Install Composer 2 or above for

## Node Setup

Install Node 16 or above & npm 8.15 or above, and follow <https://nodejs.org/en/download/current/> for more.

### Node Sass Setup

Install node sass globally.

```bash
npm i -g node-sass
```

## Project Setup

- Clone the project from github

- Run the migrations

```bash
php commands/migration.php
```

- Compile the sass files

```bash
node-sass public/scss/style.scss -o public/scss/
```

- Serve the project in PHP server at 8421

```bash
php -S localhost:8421 -t public/
```
