# E-Commerce Documentation

## Table of Contents

-   [📖 Introduction](#user-content--introduction)
-   [🚀 Key Features](#user-content--key-features)
-   [💻 Technologies Used](#user-content--technologies-used)
-   [⚙️ Getting Started](#user-content-%EF%B8%8F-getting-started)
    -   [Installation](#user-content-installation)
    -   [Configuration](#user-content-configuration)
    -   [Running the Application](#user-content-running-the-application)
    -   [Running the Daily Report](#user-content-running-the-daily-report)
    -   [Running Tests](#user-content-running-tests)
-   [📚 API Documentation](#user-content--api-documentation)
    -   [REST API Endpoints](#user-content-rest-api-endpoints)
    -   [GraphQL API Endpoints](#user-content-graphql-api-endpoints)
        -   [Queries](#user-content-queries)
        -   [Mutations](#user-content-mutations)
-   [📦 Resources](#user-content--resources)

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

-   **Backend**: PHP v8.3.9, Laravel 11
-   **Database**: MySQL v8.3.0
-   **Testing**: Pest v2.35
-   **Static Code Analysis**: PHPStan v1.11

## ⚙️ Getting Started

### Installation

1. **Clone the repository:**

    ```bash
    git clone https://gitlab.com/yousefyasser/e-commerce.git
    cd e-commerce
    ```

### Configuration

1. **Rename the `.env.example` file to `.env`:**

    ```bash
    mv .env.example .env
    ```

2. **Set up your environment variables:**

    Update the `.env` file with your database, mail, and other configuration settings.

### Running the Application

-   Run the application by building docker images and starting docker containers:

    ```bash
    docker compose up -d
    ```

### Running the daily report

-   The daily report is a command I created that sends an email every midnight to admin users with a summary of orders made in previous day, attached to the email is a spreadsheet containing each orders' details.

    ```bash
    docker compose exec web-server php artisan app:send-orders-report
    ```

### Running Tests

-   To run the unit tests, performance tests, and static code analysis:

    ```bash
    chmod +x run_tests.sh
    bash run_tests.sh
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
        "message": "Login successful",
        "data": {
            "user": {
                "id": 44,
                "name": "John Doe",
                "email": "yousefyasser@gmal.com",
                "email_verified_at": null,
                "role": "user",
                "created_at": "2024-08-27T10:29:29.000000Z",
                "updated_at": "2024-08-27T10:29:29.000000Z"
            },
            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzI0NzU0NTg2LCJleHAiOjE3MjQ3NTgxODYsIm5iZiI6MTcyNDc1NDU4NiwianRpIjoiNVlJcXZTV2ROZlJIbHJObiIsInN1YiI6IjQ0IiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.HUeDT1Orp0ImLYRN6CSUv0n7zlPgYfHS6cwJmGgph50"
        }
    }
    ```

</details>

<details>
    <summary>
        Create Category
    </summary>

-   **URL**: POST /api/categories
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
        "status": "success",
        "message": "Category created successfully",
        "data": {
            "name": "Milk",
            "description": "This is Milk",
            "parent_id": null,
            "updated_at": "2024-08-27T10:35:28.000000Z",
            "created_at": "2024-08-27T10:35:28.000000Z",
            "id": 192
        }
    }
    ```

</details>

<details>
    <summary>
        Create Product
    </summary>

-   **URL**: POST /api/products
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
        "status": "success",
        "message": "Product created successfully",
        "data": {
            "name": "milk",
            "description": "this is dairy",
            "price": 45.5,
            "category_id": 192,
            "stock": 5,
            "updated_at": "2024-08-27T10:37:21.000000Z",
            "created_at": "2024-08-27T10:37:21.000000Z",
            "id": 604
        }
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
        "status": "success",
        "message": "Orders retrieved successfully",
        "data": {
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
    }
    ```

</details>

### GraphQL API Endpoints

All GraphQL API endpoints are for users only and can be accessed via a POST request to /graphql

#### Queries

<details>
    <summary>
        Categories
    </summary>

-   Description: Retrieves all categories with their subcategories.
-   **Request Body**:
    ```graphql
    {
        categories {
            id
            name
            subcategories {
                id
                name
                description
            }
        }
    }
    ```
-   **Response**:
    ```json
    {
        "data": {
            "categories": [
                {
                    "id": "1",
                    "name": "Dairy",
                    "subcategories": [
                        {
                            "id": "2",
                            "name": "Milk",
                            "description": "This is Milk"
                        }
                    ]
                }
            ]
        }
    }
    ```

</details>

<details>
    <summary>
        Products
    </summary>

-   **Description**: Retrieves all Product details with pagination.
-   **Query Parameters**:
    -   **first**: gets only the first specified number of products from the result
    -   **page**: gets result from specified page after pagination
    -   **category**: filter products by category id
    -   **search**: filter products by name
    -   **sortBy**: sort the result by a product attribute
-   **Request Body**:
    ```graphql
    {
        products(first: 1, category: 6, search: "Milk", sortBy: PRICE_ASC) {
            data {
                id
                name
                description
                price
                category_id
                stock
                image_url
                created_at
            }
        }
    }
    ```
-   **Response**:
    ```json
    {
        "data": {
            "products": {
                "data": [
                    {
                        "id": "101",
                        "name": "milk",
                        "description": "this is dairy",
                        "price": 45.5,
                        "category_id": 6,
                        "stock": 5,
                        "image_url": null,
                        "created_at": "2024-08-21 08:28:31"
                    }
                ]
            }
        }
    }
    ```

</details>

<details>
    <summary>
        Order History
    </summary>

-   **Description**: Retrieves all previous orders made by the authenticated user.
-   **Query Parameters**:
    -   **first**: gets only the first specified number of orders from the result
    -   **page**: gets result from specified page after pagination
    -   **status**: filter products by status
    -   **sort**: sort the result by an order attribute
-   **Request Body**:
    ```graphql
    {
        orderHistory(
            status: "pending"
            sort: "created_at_desc"
            first: 1
            page: 2
        ) {
            data {
                id
                status
                total
                items {
                    product {
                        name
                    }
                    total
                }
                address {
                    address_line_1
                }
                payment_method {
                    type
                }
                created_at
                updated_at
            }
        }
    }
    ```
-   **Response**:
    ```json
    {
        "data": {
            "orderHistory": {
                "data": [
                    {
                        "id": "11",
                        "status": "pending",
                        "total": 5215.1,
                        "items": [
                            {
                                "product": {
                                    "name": "temporibus"
                                },
                                "total": 5215.1
                            }
                        ],
                        "address": {
                            "address_line_1": "243 Marcella Ports"
                        },
                        "payment_method": {
                            "type": "PayPal"
                        },
                        "created_at": "2024-08-21 08:15:08",
                        "updated_at": "2024-08-21 08:15:08"
                    }
                ]
            }
        }
    }
    ```

</details>

#### Mutations

<details>
    <summary>
        Registeration
    </summary>

-   **Description**: Registers a new user.
-   **Request Body**:

    ```graphql
    mutation ($input: CreateUserInput!) {
        registerUser(input: $input)
    }
    ```

    -   Variables

    ```json
    {
        "input": {
            "name": "name",
            "email": "username@example.com",
            "password": "Password123",
            "password_confirmation": "Password123"
        }
    }
    ```

-   **Response**:

    ```json
    {
        "data": {
            "registerUser": ["status", "message", "user data", "JWT"]
        }
    }
    ```

</details>

<details>
    <summary>
        Email Verification
    </summary>

-   **Description**: Verifies the user's email address.
-   **Request Body**:

    ```graphql
    mutation {
        verifyEmail
    }
    ```

-   **Response**:

    ```json
    {
        "data": {
            "verifyEmail": ["status", "message", "user data"]
        }
    }
    ```

</details>

<details>
    <summary>
        Add to cart
    </summary>

-   **Description**: Add a product to user's cart.
-   **Query Parameters**:
    -   product_id: ID of product to add to cart
    -   quantity: How much of that product to add to cart
-   **Request Body**:

    ```graphql
    mutation {
        addToCart(product_id: 6, quantity: 3) {
            id
            name
            price
            stock
        }
    }
    ```

-   **Response**:

    ```json
    {
        "data": {
            "addToCart": {
                "id": "6",
                "name": "consequatur",
                "price": 74.19,
                "stock": 98
            }
        }
    }
    ```

</details>

<details>
    <summary>
        Create Address
    </summary>

-   **Description**: Add a new address for the user.
-   **Request Body**:

    ```graphql
    mutation ($addressData: CreateAddressInput!) {
        createAddress(addressData: $addressData)
    }
    ```

    -   Variables

    ```json
    {
        "addressData": {
            "label": "a",
            "recipient_name": "b",
            "address_line_1": "c",
            "address_line_2": "d",
            "state": "e",
            "city": "f",
            "country": "g",
            "postal_code": "h",
            "phone_number": "i"
        }
    }
    ```

-   **Response**:

    ```json
    {
        "data": {
            "createAddress": "16"
        }
    }
    ```

</details>

<details>
    <summary>
        Checkout
    </summary>

-   **Description**: Buy all products in cart with provided payment method and address.
-   **Request Body**:

    ```graphql
    mutation {
        checkout(address_id: 1, payment_method_id: 1)
    }
    ```

-   **Response**:

    ```json
    {
        "data": {
            "checkout": "16"
        }
    }
    ```

</details>

## 📦 Resources

-   https://laravel.com/docs/11.x
-   https://lighthouse-php.com/
