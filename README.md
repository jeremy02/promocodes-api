<p align="center"><a href="https://safeboda.com/ke/" target="_blank"><img src="https://github.com/jeremy02/promocodes-api/blob/master/public/images/safeboda_logo.png?raw=true" width="400"></a></p>

# CASE STUDY FOR Software Engineer (PHP) - [SafeBoda](https://safeboda.com)

---
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

# Installation

---

## Installation Using Docker

- Clone the repo.

- cd into the root folder of the project directory.

- If there is no env file, copy the .env file from .env.example
    ```
    > cp .env.example .env
    ```

  Current .env file from the application contains settings to use a local MySQL database, with 127.0.0.1 as database host.
  Update the DB_HOST variable to point to the database service we will create in our Docker environment.
    ```
    > DB_CONNECTION=mysql
    > DB_HOST=db
    > DB_PORT=3306
    > DB_DATABASE=safeboda
    > DB_USERNAME=safeboda_user
    > DB_USERNAME=safeboda_user
    > DB_PASSWORD=pass
    ```

- Use docker-compose to build the app image and run the services

  - Build the app image
    ```
    > docker-compose build app
    ```
  - When the build is finished, run the environment in background mode
    ```
    > docker-compose up -d
    ```
  - To view information active services state, run:
    ```
    > docker-compose ps
    ```
  - Environment is up and running, but we still need to execute a couple commands to finish setting up the application
  - Run composer install to install the application dependencies:
    ```
    > docker-compose exec app composer install
    ```
  - Generate a unique application key with the artisan Laravel command-line tool
    ```
    > docker-compose exec app php artisan key:generate
    ```
  - Seed your database with test data
    ```
    > docker-compose exec app php artisan migrate:fresh --seed
    ```
  - Go to browser and access server’s domain name or IP address on port 8000:
    ```
    > http://localhost:8000 or http://{server_domain}:8000 or http://{IP}:8000
    ```

  - Run the following command to stop and remove the containers and all associated networks
    ```
    > docker-compose down
    ```
  
## Installation Locally Without Docker
- Clone the repo.

- cd into the root folder of the project directory.

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
- Sets the APP_KEY value in your .env file
    ```
    > php artisan key:generate
    ```
- Seed your database with test data
    ```
    > php artisan migrate:fresh --seed
    ```
- Start a development server by running one/any of the below commands. The default port is 8000 but you can set port to 8080 for the demo
    ```
    > php artisan serve --port=8080
    > php artisan serve
    ```
- Open [http://localhost:8080](http://localhost:8080) or [http://localhost:8000](http://localhost:8000) depending on the port you are running
  your server on


## Tests

---

- ##### If you have run or installed your application using docker run the following command to run the tests
    ```
    > docker-compose exec app php artisan test
    ```

  - To test deletion of the promo codes that are already expired OR to see how many promo codes will be pruned or are about to expire
      ```
      > docker-compose exec app php artisan model:prune --pretend
      ```
- ##### If you have run or installed your application without docker, To run the tests run the following command
    ```
    > php artisan test
    ```
 
- To test deletion of the promo codes that are already expired OR to see how many promo codes will be pruned or are about to expire
    ```
    > php artisan model:prune --pretend
    ```

## Scaling the application(Using Docker)

---

 - One instance of the service may not be enough to sufficiently handle all the traffic to the application.
 - Docker supports scaling of services by creating multiple instances of a service, by using the --scale flag.
 - 
 #### To make these changes, update the nginx service configuration to by removing the container name and replacing it with the the following
 ```
    nginx:
        image: nginx:alpine
        restart: unless-stopped
        ports:
            - 8000:80
        volumes:
            - ./:/var/www
            - ./docker-compose/nginx:/etc/nginx/conf.d/
        networks:
            - safeboda
```
