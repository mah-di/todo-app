## To-do App
A simple to-do task managing app built with Laravel backend and VueJS frontend

## How To Run
Clone the repo `git clone https://github.com/mah-di/todo-app.git`

#### Setup backend (Laravel)
- from the project root: `cd backend`
- install dependencies: `composer install`
- generate .env file: `cat .env.example > .env`
- generate application key: `php artisan key:generate`
- run migrations: `php artisan migrate`
- serve backend: `php artisan serve`
#### Setup frontend (VueJS)
- from the project root: `cd frontend`
- install dependencies: `npm install`
- serve frontend: `npm run dev`

## Main Features
- Basic Laravel session based authentication
- Basic authorization (users can access/manage only their own to-dos)
- SPA frontend, communicates with backend through APIs
- Basic and clean UI, styled with TailwindCSS
- Basic CRUD functionality for managing to-dos
- Auth store managed with Pinia
- Simple toast notifications

## Tech Stack
- Laravel
- SQLite
- VueJS
- TailwindCSS
- Axios
- Pinia
- Vue-Toastification
