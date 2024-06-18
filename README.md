# ddev-laravel

Demo repository for Laravel v11 with [DDEV](https://ddev.com/).

Based on the tutorial [Install Laravel DDEV (Docker)](https://ddev.com/blog/ddev-local-for-laravel-teams/).

You can run this

1. on [your local laptop](#1-local-setup)

## Local setup

Install [DDEV](https://ddev.com/get-started/) and run the following commands:

```bash
git clone https://github.com/MinhTri1911/flappy-bird-laravel.git

cd flappy-bird-laravel/

# automatically creates .env with correct db settings
ddev start

# install dependencies
ddev composer install && ddev npm install

# create key for .env
ddev artisan key:generate

# Change db config
DB_CONNECTION="mysql"
DB_HOST="db"
DB_PORT="3306"
DB_DATABASE="db"
DB_USERNAME="db"
DB_PASSWORD="db"

# create db tables
ddev artisan migrate

# seeding data
ddev artisan db:seed

# Open your website in browser, ...
ddev launch

# ... and hit reload in browser. Vite should work now 🥳
```

Your site is accessible via https://ddev-flappy-bird-laravel.ddev.site.

You could also import a database dump via `ddev import-db --file=dump.sql.gz` or use [`ddev pull`](https://ddev.readthedocs.io/en/stable/users/providers/) to setup a project. Use `ddev sequelace` to view your database.
