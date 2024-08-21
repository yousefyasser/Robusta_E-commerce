# E-Commerce Documentation

## Table of Contents

-   [📖 Introduction](#introduction)
-   [🚀 Key Features](#key-features)
-   [💻 Technologies Used](#technologies-used)
-   [⚙️ Getting Started](#getting-started)
    -   [Installation](#installation)
    -   [Configuration](#configuration)
    -   [Application Setup](#application-setup)
    -   [Running the Application](#running-the-application)
    -   [Running the Daily Report](#running-the-daily-report)
    -   [Running Static Code Analysis](#running-static-code-analysis)
    -   [Running Tests](#running-tests)
-   [📚 API Documentation](#api-documentation)
    -   [REST API Endpoints](#rest-api-endpoints)
    -   [GraphQL API Endpoints](#graphql-api-endpoints)
        -   [Queries](#queries)
        -   [Mutations](#mutations)
-   [📦 Resources](#resources)

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

## 📦 Resources

-   https://laravel.com/docs/11.x
-   https://lighthouse-php.com/
