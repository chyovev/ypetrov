# Yosif Petrov website
A website in memory of the famous Bulgarian poet Yosif Petrov containing his works; press articles, videos and photographs of him, as well as other useful information about him.

#### Preview: [https://yosifpetrov.com](https://yosifpetrov.com)

### How to set up locally via Docker

1. Clone project
2. Copy `.env.example` to `.env`
3. Build the Docker services: `docker compose build`
4. Create and start the Docker containers: `docker compose up -d` (`-d` is detached mode, containers are being run in the background).
5. Install dependencies via composer: `docker exec -it ypetrov-app composer install`.
6. Generate an application key: `docker exec -it ypetrov-app php artisan key:generate`
7. Run all migrations to create database tables: `docker exec -it ypetrov-app php artisan migrate --step`
8. Populate tables: `docker exec -it ypetrov-app php artisan db:seed`. This will create a sample user to access the administrative panel (see the `UserSeeder` class).