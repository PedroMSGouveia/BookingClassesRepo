
# Booking classes

Booking Classes - ABC Glofox Take-Home Task Assignment
## Features

- Swagger UI for Ease of Use
- GET, POST, DELETE Endpoints for Both Classes and Bookings - Although only the POST is specified in the requirements, both GET and DELETE methods are implemented for a more realistic approach.
- Custom Classes for Each Type of Request Parameters with Proper Validation and Specified Error Messages
- Custom Exceptions for Each Kind of Error (Class/Booking Not Found, Duplicate Class/Booking, Request Format Exception)
- Usage of Two Databases: One for Development and One for Testing
- Full Coverage Testing (Controllers, Services, Repositories, and Validators)
## Configuration

To run this project, you need to follow these configuration steps:

Start by cloning this repository using the following command:

```bash
git clone https://github.com/PedroMSGouveia/BookingClassesRepo.git
```

Install Xampp at: https://www.apachefriends.org/
- Ensure you select the MySQL option to install the MySQL server.

Open the XAMPP control panel and start both the Apache and MySQL modules.
## Installation

To install the project, run the following command:

```bash
  composer install
```

After that, migrate the database tables for deployment and a separate one for testing:

```bash
  php artisan migrate
```

**This command will ask you if you want to create the "laravel" database; type "Yes" to confirm.**

Testing Database:

```bash
  php artisan migrate --env=testing
```

**This command will ask you if you want to create the "testing_db" database; type "Yes" to confirm.**

The migration creates the development and testing databases with the corresponding tables (classes and bookings).
## Deployment

To deploy this project, run the following command:

```bash
  php artisan serve
```
Ctrl+click on the server link shown in the terminal, or use this link: http://127.0.0.1:8000.

After that, you should be able to access the Swagger API documentation, where you can test all endpoints.

## Running Tests

To run tests, execute the following command:

```bash
  php artisan test
```
This will display the results of all 34 tests in the terminal.
