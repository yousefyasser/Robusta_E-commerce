# E-Commerce Documentation

## Table of Contents

-   [📖 Introduction](#user-content--introduction)
-   [🚀 Key Features](#user-content--key-features)
-   [💻 Technologies Used](#user-content--technologies-used)
-   [⚙️ Getting Started](#user-content-%EF%B8%8F-getting-started)
    -   [Installation](#user-content-installation)
    -   [Configuration](#user-content-configuration)
    -   [Application Setup](#user-content-application-setup)
    -   [Running the Application](#user-content-running-the-application)
    -   [Running the Daily Report](#user-content-running-the-daily-report)
    -   [Running Static Code Analysis](#user-content-running-static-code-analysis)
    -   [Running Tests](#user-content-running-tests)
-   [📚 API Documentation](#user-content--api-documentation)
    -   [REST API Endpoints](#user-content-rest-api-endpoints)
    -   [GraphQL API Endpoints](#user-content-graphql-api-endpoints)
        -   [Queries](#user-content-queries)
        -   [Mutations](#user-content-mutations)
-   [📦 Resources](#user-content-resources)

## 📖 Introduction

This documentation provides an overview of the E-Commerce API, including the available endpoints for both REST and GraphQL, configuration instructions, and other essential details to help developers integrate with the API.

## 🚀 Key Features

-   User authentication and authorization
-   Product catalog management
-   Shopping cart functionality
-   Order processing and management
-   Email for confirming registration / e-mail verification
-   Email for order confirmation upon checkout
-   Daily report sent to admin users by spreadsheet attached to e-mail with orders made

## 💻 Technologies Used

-   **Backend**: PHP, Laravel
-   **Database**: MySQL
-   **Testing**: Pest
-   **Static Code Analysis**: PHPStan

## ⚙️ Getting Started

### Installation

1. **Clone the repository:**

    ```bash
    git clone https://gitlab.com/yousefyasser/e-commerce.git
    cd e-commerce
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

### Configuration

1. **Rename the `.env.example` file to `.env`:**

    ```bash
    mv .env.example .env
    ```

2. **Set up your environment variables:**

    Update the `.env` file with your database, mail, and other configuration settings.

### Application Setup

1. **Run the migrations to set up the database schema:**

    ```bash
    php artisan migrate --seed
    ```

2. **Generate Laravel app key & JWT secret key:**

    ```bash
    php artisan key:generate
    php artisan jwt:secret
    ```

### Running the Application

-   Start the application using the built-in PHP server:

    ```bash
    php artisan serve
    ```

### Running the daily report

-   The daily report is a command I created that sends an email every midnight to admin users with a summary of orders made in previous day, attached to the email is a spreadsheet containing each orders' details.

    ```bash
    php artisan app:send-orders-report
    ```

### Running Static Code Analysis

-   To run the static code analysis using phpstan:

    ```bash
    vendor/bin/phpstan analyse --memory-limit=1G
    ```

### Running Tests

-   To run the unit tests using Pest:

    ```bash
    php artisan test
    ```

## 📚 API Documentation

The application uses JWT (JSON Web Tokens) for authentication. Users must register and log in to receive a token, which must be included in the Authorization header of all subsequent requests.

-   **Example Usage**:
    ```json
    Authorization: Bearer <YOUR_ACCESS_TOKEN>
    ```

### REST API Endpoints

All REST API endpoints are for admins only except Login.

<details>
    <summary>
        Login
    </summary>

-   **URL**: POST /api/login
-   **Description**: Login as an existing user/admin.
-   **Request Body**:
    ```json
    {
        "email": "username@example.com",
        "password": "Password123"
    }
    ```
-   **Response**:
    ```json
    {
        "status": "success",
        "user": {
            "id": 6,
            "name": "John Doe",
            "email": "username@example.com",
            "email_verified_at": null,
            "role": "user",
            "created_at": "2024-08-21T05:18:20.000000Z",
            "updated_at": "2024-08-21T05:18:20.000000Z"
        },
        "token": "JWT"
    }
    ```

</details>

<details>
    <summary>
        Create Category
    </summary>

-   **URL**: POST /api/categories/create
-   **Description**: Creates a new Category.
-   **Request Body**:
    ```json
    {
        "name": "Milk",
        "description": "This is Milk",
        "parent_id": 1
    }
    ```
-   **Response**:
    ```json
    {
        "id": 31,
        "name": "Milk",
        "description": "This is Milk",
        "parent_id": 1,
        "updated_at": "2024-08-21T05:34:35.000000Z",
        "created_at": "2024-08-21T05:34:35.000000Z"
    }
    ```

</details>

<details>
    <summary>
        Create Product
    </summary>

-   **URL**: POST /api/products/create
-   **Description**: Creates a new Product.
-   **Request Body**
    ```json
    {
        "name": "Milk",
        "description": "This is Milk",
        "price": 45.5,
        "category_id": 6,
        "stock": 5
    }
    ```
-   **Response**:
    ```json
    {
        "id": 101,
        "name": "Milk",
        "description": "This is Milk",
        "price": 45.5,
        "category_id": 6,
        "stock": 5,
        "created_at": "2024-08-21T05:28:31.000000Z",
        "updated_at": "2024-08-21T05:28:31.000000Z"
    }
    ```

</details>

<details>
    <summary>
        List Orders
    </summary>

-   **URL**: GET /api/orders?page=1&sort=total_asc&status=pending
-   **Description**: Retrieves details of all orders paginated.
-   **Optional Query Parameters**:
    -   **page**: Retrieve a specific page from paginated result
    -   **sort**: Sort Orders by a specific column
    -   **status**: Filter Orders by their status
-   **Response**:
    ```json
    {
        "current_page": 1,
        "data": [
            {
                "id": 3,
                "user_id": 1,
                "address_id": 3,
                "payment_method_id": 1,
                "status": "pending",
                "total": 105.87,
                "created_at": "2024-08-21T05:15:07.000000Z",
                "updated_at": "2024-08-21T05:15:07.000000Z",
                "items": [
                    {
                        "id": 11,
                        "order_id": 3,
                        "product_id": 36,
                        "quantity": 7,
                        "price": 409.86,
                        "created_at": "2024-08-21T05:15:08.000000Z",
                        "updated_at": "2024-08-21T05:15:08.000000Z",
                        "total": 2869.02,
                        "product": {
                            "id": 36,
                            "name": "vero",
                            "description": "Quam maxime perspiciatis vel voluptatem praesentium at id.",
                            "price": 23.83,
                            "category_id": 29,
                            "stock": 94,
                            "image_url": "https://via.placeholder.com/640x480.png/00ffdd?text=corrupti",
                            "created_at": "2024-08-21T05:15:07.000000Z",
                            "updated_at": "2024-08-21T05:15:07.000000Z"
                        }
                    }
                ],
                "address": {
                    "id": 3,
                    "label": "expedita",
                    "recipient_name": "Brittany Lindgren",
                    "address_line_1": "34833 Eichmann Stream Apt. 062",
                    "address_line_2": "Apt. 935",
                    "city": "South Maximoside",
                    "state": "New Jersey",
                    "postal_code": "62491-8853",
                    "country": "Norway",
                    "phone_number": "920.997.0276",
                    "user_id": 1
                },
                "payment_method": {
                    "id": 1,
                    "user_id": 1,
                    "type": "Credit Card",
                    "last_four": "1945"
                }
            }
        ]
    }
    ```

</details>

## 📦 Resources

-   https://laravel.com/docs/11.x
-   https://lighthouse-php.com/
