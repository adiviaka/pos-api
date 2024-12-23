# POS API Project

## Project Overview

This project is a RESTful API designed to simulate a simple Point of Sale (POS) system. The API allows users to register, log in using JWT authentication, and interact with product and transaction data.

Features:
Authentication: Users can register and log in with JWT-based authentication.
Product Management: Admins can perform CRUD operations on products, including searching and filtering by category.
Transaction Management: Transactions are created to track sales, including product details and stock updates.

This API is built using Laravel, with PostgreSQL as the database and JWT for authentication.

## How to Run

1. Clone the repository

```bash
git clone https://github.com/adiviaka/pos-api.git
cd pos-api
```

2. Install dependencies
   Make sure you have Composer and PHP installed. Run the following command to install the project dependencies.

```bash
composer install
```

3. Set up the environment
   Duplicate the .env.example file and rename it to .env
   Update your .env file woth your database credentials

```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pos_api
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

4. Generate the application key

```bash
php artisan ket:generate
```

5. Run migrations

```bash
php artisan migrate
```

6. Serve the application

```bash
php artisan serve
```

## API Documentation

Link to API Documentation: https://documenter.getpostman.com/view/25862030/2sAYJ3FhLT

## Database Design (ERD)

Below is the entity-relationship diagram (ERD) that represents the database design for this project.

Entities:

-   Users: Stores information about users.
-   Products: Stores information about products.
-   Transactions: Stores information about sales transactions.
-   Transaction Details: Stores the details for each transaction, including product details.

https://drive.google.com/file/d/1Q4QAqcdz_j8dqlmsnjp1O9Lc2uicT4tZ/view?usp=sharing
