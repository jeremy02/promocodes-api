<p align="center"><a href="https://safeboda.com/ke/" target="_blank"><img src="https://github.com/jeremy02/promocodes-api/blob/master/public/images/safeboda_logo.png?raw=true" width="400"></a></p>

# CASE STUDY FOR Software Engineer (PHP) - [SafeBoda](https://safeboda.com)
Intro: SafeBoda wants to give out promo codes worth x amount during events so people can get
free rides to and from the event. The flaw with that is people can use the promo codes without
going to the event.
Task: Implement a Promo code API with the following features.
- Generation of new promo codes for an event
- The promo code is worth a specific amount of ride
- The promo code can expire
- Can be deactivated
- Return active promo codes
- Return all promo codes
- Only valid when user’s pickup or destination is within x radius of the event venue
- The promo code radius should be configurable
- To test the validity of the promo code, expose an endpoint that accepts origin,
  destination, the promo code.
- The API should return the promo code details and a polyline using the destination and
  origin if the promo code is valid and an error otherwise.

Please submit the code as if you intended to ship it to production. The details matter. Tests are expected, as is well written, simple idiomatic code.

## Installation

- If there is no env file, copy the .env file from .env.example
    ```
    > cp .env.example .env
    ```

- Edit or add your database credentials in the `.env` file that you just copied above.
    ##### NB: In our case we have used [PostgreSQL 14](https://www.postgresql.org/)
- Install the laravel project dependencies
    ```
    > composer install
    ```
-  Sets the APP_KEY value in your .env file
    ```
    > php artisan key:generate
    ```
-  Seed your database with test data
    ```
    > php artisan migrate:fresh --seed
    ```
-  Start a development server by running one/any of the below commands. The default port is 8000 but you can set port to 8080 for the demo
    ```
    > php artisan serve --port=8080
    > php artisan serve
    ```
   
## View

Open [http://localhost:8080](http://localhost:8080) or [http://localhost:8000](http://localhost:8000) depending on the port you are running
your server on.

## Tests

- To run the tests run the following command
    ```
    > php artisan test
    ```
 
- To test deletion of the promo codes that are already expired to see how many promo codes will be pruned if the command were to actually run
    ```
    > php artisan model:prune --pretend
    ```
