# Booking classes

ABC Glofox take-home task assignment
## Configuration

In order to run this project it is need to follow there Configuration steps:

First Step: Install Xampp at: https://www.apachefriends.org/

**Make sure you tick MySql box to install mysql server**

After that just run Xampp and start Apache and Mysql
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
