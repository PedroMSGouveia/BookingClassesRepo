
# Booking classes

ABC Glofox take-home task assignment
## Features

- Swagger UI for ease of use
- GET POST and DELETE endpoints both for classes and bookings
- Custom classes for each type of request params with proper validation and specified error messages
- Custom exceptions for each kind of error(Class/booking not found, Duplicate class/booking, Request format exception)
- Usage of 2 databases, one for dev one for testing
- Full coverage testing (Controllers, Services, Repositories and Validators)
## Configuration

To run this project, you need to follow these configuration steps:

Start by cloning this same repository using the following command:
```bash
git clone https://github.com/PedroMSGouveia/BookingClassesRepo.git
```

Install Xampp at: https://www.apachefriends.org/

**Make sure you tick MySql box to install mysql server**

After that just open Xampp control panel and press start no both Apache and Mysql modules
## Installation

In order to install the project just run the command bellow

```bash
  composer install
```

After that we will need to migrate database tables, we will do that for deployment and a separate one for testing.

```bash
  php artisan migrate
```

**This command should ask you if you want to insert "laravel" database, just type "Yes".**

Now for testing database:

```bash
  php artisan migrate --env=testing
```

**This command, as the one before, should ask you if you want to insert "testing_db" database, just type "Yes".**

The migration is made in order to create the dev and testing databases with the corresponding tables (classes and bookings)
## Deployment

To deploy this project run

```bash
  php artisan serve
```
Press ctrl+click on the server link that is shown at the terminal.
Or Just use this link: http://127.0.0.1:8000

After that you should be able to see swagger api-documentation, where you can test all endpoints.

## Running Tests

To run tests, run the following command

```bash
  php artisan test
```
That should output all 29 passed tests to the terminal.
