# Swork

A planning board developed by Indic Amigo Akademi.

## Setup

After cloning the project, open the terminal in the public folder and run the following command

```bash
php -S localhost:8421 -t public/
```

PHP dev server starts at <http://localhost:8421>.

## SCSS Compile

```bash
node-sass public/scss/style.scss -o public/scss/
```

## Make DBMS migration

```bash
php commands/migration.php
```

## Archive

This repo is archived on 10 September, 2023 and kept public for academic purpose.
