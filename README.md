# Swork

A planning board developed by Indic WebDev Team.

## Setup

After cloning the project, open the terminal in the public folder and run the following command

```bash
/opt/lampp/bin/php -S localhost:8421 -t public/
```

PHP dev server starts at <http://localhost:8421>.

## SCSS Compile

```bash
node-sass public/scss/style.scss -o public/scss/
```

## Make DBMS migration

```bash
/opt/lampp/bin/php commands/migration.php
```
