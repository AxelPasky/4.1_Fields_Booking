# Field Booking System

A modern web application built with the Laravel framework for managing and booking sports fields. It provides a simple and intuitive interface for users to book available fields and a comprehensive admin panel for facility owners to manage their properties.

## Key Features

-   **Clean & Scalable Architecture:** Implements a multi-layered architecture using a Service Layer, Form Requests, and Policies to ensure a strong separation of concerns and high-quality, maintainable code.
-   **User Authentication:** Secure registration, login, and password reset functionality.
-   **Role-Based Access Control:** Distinct roles for regular users and administrators, managed via dedicated Policy classes.
    -   **Admins:** Can perform full CRUD (Create, Read, Update, Delete) operations on sports fields.
    -   **Users:** Can view available fields, create, view, and cancel their own future bookings.
-   **Field Management (Admin):**
    -   Dynamic, real-time search for fields.
    -   Create, edit, and delete fields, including details like name, type, price, description, and image uploads.
-   **Booking Management (User):**
    -   Intuitive form for booking fields with robust, server-side validation to prevent conflicts and invalid entries.
    -   Personal dashboard (`My Bookings`) to view and manage all personal bookings.
-   **Email Notifications:**
    -   Automatic email notifications for booking creation, updates, and cancellations.
-   **Modern Frontend:**
    -   Responsive design built with Tailwind CSS.
    -   Dynamic UI components powered by Livewire 3 for a seamless, single-page application feel.
    -   Modular JavaScript managed via Vite.

## Tech Stack

-   **Backend:** Laravel 11, PHP 8.2
-   **Frontend:** Livewire 3, Tailwind CSS, Alpine.js
-   **Database:** MySQL
-   **Development Tools:** Vite, Composer, NPM

## Project Architecture

This application follows a clean, multi-layered architecture to ensure a strong separation of concerns, making the codebase scalable, maintainable, and easy to test.

-   **Controllers (`app/Http/Controllers`):** Act as the entry point for HTTP requests. Their sole responsibility is to orchestrate the flow of data, calling the appropriate services and returning an HTTP response. They contain no business logic.
-   **Service Layer (`app/Services`):** This is the core of the application's business logic. Classes like `BookingService` and `FieldService` encapsulate all the complex operations (e.g., calculating prices, handling file uploads, sending notifications), ensuring this logic is reusable and decoupled from the HTTP layer.
-   **Form Requests (`app/Http/Requests`):** Handle all validation and authorization logic for incoming requests. This keeps the controllers clean and focused, as the request is already validated and authorized before it even reaches the controller's method.
-   **Policies (`app/Policies`):** Define the authorization rules for specific models (e.g., who can update or delete a booking). This provides a granular and centralized way to manage user permissions.
-   **Eloquent Models (`app/Models`):** Represent the data layer, managing the interaction with the database.

This structure ensures that each part of the application has a single, well-defined responsibility.

---

## Installation and Setup

Follow these steps to get the project up and running on your local machine.

### Prerequisites

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   A database server (e.g., MySQL)

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/your-repository.git
cd your-repository
```

### 2. Install Dependencies

Install both PHP and JavaScript dependencies.

```bash
composer install
npm install
```

### 3. Environment Configuration

Create your local environment file and generate the application key.

```bash
cp .env.example .env
php artisan key:generate
```

Next, open the `.env` file and configure your database connection details (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

For local email testing, it is recommended to use [Mailpit](https://github.com/axllent/mailpit). The `.env` file is already configured for it by default.

### 4. Database Migration and Seeding

Run the database migrations to create the tables and the seeders to populate the database with demo data (admin user, regular user, and sample fields).

```bash
php artisan migrate:fresh --seed
```

### 5. Storage Link

Create the symbolic link to make uploaded images publicly accessible.

```bash
php artisan storage:link
```

### 6. Build Assets and Run the Server

Finally, build the frontend assets and start the local development server.

```bash
npm run build
php artisan serve
```

The application will be available at `http://127.0.0.1:8000`.

---

## Demo Credentials

The database seeder creates the following users for testing purposes:

#### Administrator Account

-   **Email:** `admin@example.com`
-   **Password:** `password`

#### Regular User Account

-   **Email:** `user@example.com`
-   **Password:** `password`

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).