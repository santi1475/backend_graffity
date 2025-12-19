# Backend Graffity API

A robust RESTful API built with **Laravel 12**, designed to serve as the backend for the Graffity system. This project features secure JWT authentication and a comprehensive Role-Based Access Control (RBAC) system.

## 🚀 Features

- **Secure Authentication**: Implemented using `php-open-source-saver/jwt-auth` for stateless JSON Web Token authentication.
- **Role-Based Access Control (RBAC)**: Fine-grained permissions management using `spatie/laravel-permission`.
- **Scalable Architecture**: Standard Laravel structure ready for modular expansion.
- **Core Modules** (Based on Permissions):
  - User Management
  - Role & Permission Management
  - Inventory (Products & Categories)
  - Client Management
  - Sales Processing
  - Electronic Notes & Remission Guides

## 🛠️ Tech Stack

- **Framework:** Laravel 12
- **Language:** PHP ^8.2
- **Database:** MySQL / SQLite (Configurable)
- **Auth:** JWT (JSON Web Tokens)
- **Permissions:** Spatie Laravel Permission

## 📋 Prerequisites

Ensure you have the following installed on your local machine:

- [PHP 8.2+](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [PostgreSQL](https://www.postgresql.org) (or your preferred database)

## 🔧 Installation

1. **Clone the repository**
   ```
   git clone (https://github.com/your-username/backend-graffity.git)
   cd backend-graffity
2. **Install dependencies**
    ```
    composer install
    ```
3. **Environment Configuration**
    Copy the example environment file and configure your database credentials:

    ```
    cp .env.example .env
    ```

    *Update **DB_DATABASE**, **DB_USERNAME**, and **DB_PASSWORD** in the `.env file.`*
    <br>
4. **Generate Application & JWT Keys**
    ```
    php artisan key:generate
    php artisan jwt:secret
    ```
5. **Run Migrations and Seeders**
    This will set up the database tables and create the default **Super-Admin** user and permissions.
    ```
    php artisan migrate --seed
    ```
6. **Serve the Application**
    ```
    php artisan serve
    ```
    The API will be available at `http://localhost:8000`.

## 🔐 API Endpoints

Here are the primary routes defined in `routes/api.php`.   
    
### Authentication (`/api/auth`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/register` | Register a new user |
| POST | `/login` | Login and retrieve JWT token |
| POST | `/me` | Get details of the authenticated user |

### Roles (`/api/roles`)

**Requires Authentication**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/roles` | List all roles (with pagination) |
| POST | `/roles` | Create a new role |
| PUT | `/roles/{id}` | Update an existing role |
| DELETE | `/roles/{id}` | Delete a role |

## 🧪 Testing

To run the automated tests provided by PHPUnit:
```
php artisan test
```

## 📄 License
This project is open-sourced software licensed under the [Apache License](https://github.com/santi1475/backend_graffity/blob/main/LICENSE).

