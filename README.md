# Lumen Courses API

API RESTful para gestión de cursos, estudiantes e inscripciones.

## Requisitos
- PHP 8.2
- Composer
- MySQL (o PostgreSQL)
- Extensiones: pdo, mbstring, json

## Instalación
1. Clonar repo
2. `composer install`
3. Copiar `.env.example` a `.env` y configurar DB
4. `php artisan migrate`
5. `php artisan db:seed`
6. `php -S localhost:8000 -t public`

## Autenticación
- Registrar: `POST /api/auth/register` -> devuelve `api_token`
- Login: `POST /api/auth/login` -> devuelve `api_token`
- Usar cabecera: `Authorization: Bearer {api_token}`

## Endpoints
- `GET /api/students` (filtros: q, nationality, sort, dir, per_page)
- `GET /api/students/{id}`
- `POST /api/students`
- `PUT /api/students/{id}`
- `DELETE /api/students/{id}`
- Igual para `/api/courses`
- Enrollments: `POST /api/enrollments`, `GET /api/enrollments?student_id=&course_id=`, `DELETE /api/enrollments/{id}`

## Tests
`vendor/bin/phpunit`


