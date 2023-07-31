# Verification Portal

REST API with Laravel where an authenticated user sends a JSON file and receives a verification result as a response

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [Running Tests](#running-tests)

## Prerequisites

Before you can run the application, you'll need the following tools and software installed on your machine:

- PHP (minimum version: 8.x)
- Composer (https://getcomposer.org/)
- Node.js and npm (https://nodejs.org/)
- MySQL or any other supported database (optional, depending on your app's requirements)

## Installation

To install the project and its dependencies, follow these steps:

1. Clone the repository from GitHub:

   ```bash
   git clone https://github.com/aungkoko0606/aung-verification-portal.git
   cd {your-project}
   
2. Install PHP dependencies using Composer

   ```bash
   composer install
   
## Configuration

Follow the following steps to configure the application

1.) Create the `.env` file according to the content from `.env.example`
(If you want to use my RDS db credentials, you can reach out to me)

2.) Generate the application key with:

    php artisan key:generate

3.) Run the migration files

    php artisan migrate

## Running the application

As usual, you can run the application with the command

    php artisan serve

## Running tests

Lastly, you can try run the unit tests that I've created

    php artisan test --filter JsonFileVerificationProviderTest
    php artisan test --filter VerificationControllerTest



