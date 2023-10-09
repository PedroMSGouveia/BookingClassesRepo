
# Booking classes

ABC Glofox take-home task assignment
## Features

- Swagger UI for ease of test
- GET POST and DELETE endpoints both for classes and bookings
- Request validators with specified error messages
- Custom validation exceptions
- Full coverage testing (Controllers, Services, Repositories and Validators)
## Configuration

To run this project, you need to follow these configuration steps:

Start by cloning this same repository using the following command:
```bash
git clone https://github.com/PedroMSGouveia/BookingClassesRepo.git
```

Install Xampp at: https://www.apachefriends.org/

**Make sure you tick MySql box to install mysql server**

After that just run Xampp then start Apache and Mysql
## Installation

In order to install the project just run the command bellow

```bash
  composer install
```

After that we will need to migrate database tables, we will do that for deployment and a seperate one for testing.

```bash
  php artisan migrate
```

This command should ask you if you want to insert "laravel" database, just type "Yes". 

Now for testing database:

```bash
  php artisan migrate --env=testing
```

This command, as the one before, should ask you if you want to insert "testing_db" database, just type "Yes". 
## Deployment

To deploy this project run

```bash
  php artisan serve
```
Press ctrl+click on the server link that is shown at the terminal.

After that you should be able to see swagger api-documentation, where you can test all endpoints.

## Running Tests

To run tests, run the following command

```bash
  php artisan test
```
That should output all 29 passed tests to the terminal.
