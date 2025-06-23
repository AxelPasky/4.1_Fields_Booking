# Field Booking System

A modern web application built with the Laravel framework for managing and booking sports fields. It provides a simple and intuitive interface for users to book available fields and a comprehensive admin panel for facility owners to manage their properties.

## Key Features

-   **User Authentication:** Secure registration, login, and password reset functionality.
-   **Role-Based Access Control:** Distinct roles for regular users and administrators.
    -   **Admins:** Can perform full CRUD (Create, Read, Update, Delete) operations on sports fields.
    -   **Users:** Can view available fields, create, view, and cancel their own bookings.
-   **Field Management (Admin):**
    -   Dynamic, real-time search for fields.
    -   Create, edit, and delete fields, including details like name, type, price, description, and availability status.
    -   Image upload for each field.
-   **Booking Management (User):**
    -   Intuitive form for booking fields with robust date and time validation to prevent conflicts and invalid entries.
    -   Personal dashboard (`My Bookings`) to view and manage all personal bookings.
-   **Email Notifications:**
    -   Automatic email notifications are sent to users when their booking is cancelled due to a field being deleted by an admin.
-   **Modern Frontend:**
    -   Responsive design built with Tailwind CSS.
    -   Dynamic UI components powered by Livewire 3 for a seamless, single-page application feel.
    -   Custom, professional landing page with a video background.

## Tech Stack

-   **Backend:** Laravel 11, PHP 8.2
-   **Frontend:** Livewire 3, Tailwind CSS, Alpine.js
-   **Database:** MySQL
-   **Development Tools:** Vite, Composer, NPM

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

For local email testing, it is recommended to use [Mailpit](https://github.com/axllent/mailpit). The `.env` file is already configured for it by default:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
```

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