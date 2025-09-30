# Lumen Courses API

API RESTful para gestión de cursos, estudiantes e inscripciones construida con Lumen (Laravel micro-framework).

## Requisitos
- PHP 8.2+
- Composer
- MySQL (o PostgreSQL)
- Extensiones PHP: pdo, mbstring, json

## Instalación

1. Clonar el repositorio
```bash
git clone git@github.com:LadivReese/lm-desafio-idesa.git
cd lm-desafio-idesa
```

2. Instalar dependencias
```bash
composer install
```

3. Configurar variables de entorno
```bash
cp .env.example .env
```
Editar `.env` y configurar la conexión a la base de datos.

4. Ejecutar migraciones
```bash
php artisan migrate
```

5. Ejecutar seeders (opcional)
```bash
php artisan db:seed
```

6. Iniciar servidor de desarrollo
```bash
php -S localhost:8000 -t public
```

## Autenticación

Todos los endpoints (excepto registro y login) requieren autenticación mediante Bearer Token.

### Registro de usuario
```http
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123"
}
```

**Respuesta:**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

### Login
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Respuesta:**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

### Usar el token
Incluir en todas las peticiones autenticadas:
```http
Authorization: Bearer {token}
```

## Endpoints

### 🔍 Utilidades

#### Listar todas las rutas
```http
GET /routes
```

---

### 👨‍🎓 Estudiantes

#### Listar estudiantes
```http
GET /api/students/lists
Authorization: Bearer {token}
```

**Query parameters (opcionales):**
- `name` - Búsqueda por nombre
- `email` - Búsqueda por email
- `nationality` - Filtrar por nacionalidad
- `sort` - Campo para ordenar (name, email, birthdate)
- `dir` - Dirección (asc, desc)
- `per_page` - Registros por página (default: 15)

#### Obtener un estudiante
```http
GET /api/students/{id}
Authorization: Bearer {token}
```

#### Crear estudiante
```http
POST /api/students/create
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "birthdate": "1995-05-15",
  "nationality": "Paraguayan"
}
```

#### Actualizar estudiante
```http
PUT /api/students/update/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Juan Pérez Updated",
  "email": "juan.updated@example.com"
}
```

#### Eliminar estudiante
```http
DELETE /api/students/delete/{id}
Authorization: Bearer {token}
```

---

### 📚 Cursos

#### Listar cursos
```http
GET /api/courses/lists
Authorization: Bearer {token}
```

**Query parameters (opcionales):**
- `title` - Búsqueda por nombre o descripción
- `description` - Búsqueda por nombre o descripción
- `sort` - Campo para ordenar
- `dir` - Dirección (asc, desc)
- `per_page` - Registros por página

#### Obtener un curso
```http
GET /api/courses/{id}
Authorization: Bearer {token}
```

#### Crear curso
```http
POST /api/courses/create
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Introducción a Laravel",
  "description": "Curso básico de Laravel framework",
  "credits": 3
}
```

#### Actualizar curso
```http
PUT /api/courses/update/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Laravel Avanzado",
  "description": "Curso avanzado actualizado"
}
```

#### Eliminar curso
```http
DELETE /api/courses/delete/{id}
Authorization: Bearer {token}
```

---

### 📝 Inscripciones (Enrollments)

#### Listar inscripciones
```http
GET /api/enrollments/lists
Authorization: Bearer {token}
```

**Query parameters (opcionales):**
- `student_id` - Filtrar por estudiante
- `course_id` - Filtrar por curso

#### Crear inscripción
```http
POST /api/enrollments/create
Authorization: Bearer {token}
Content-Type: application/json

{
  "student_id": 1,
  "course_id": 2
}
```

#### Eliminar inscripción
```http
DELETE /api/enrollments/delete/{id}
Authorization: Bearer {token}
```

---

## Tests

Ejecutar todos los tests:
```bash
vendor/bin/phpunit
```

Ejecutar tests con coverage:
```bash
vendor/bin/phpunit --coverage-html coverage
```

Ejecutar un test específico:
```bash
vendor/bin/phpunit --filter test_can_create_student
```

## Tecnologías

- **Framework:** Lumen 10.x
- **PHP:** 8.2+
- **Base de datos:** MySQL/PostgreSQL
- **Testing:** PHPUnit 10.x

## Licencia

MIT License