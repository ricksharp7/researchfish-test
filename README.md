# Rick Sharp Code Challenge Submission

## Setting up a local environment

#### Requirements
- [Composer](https://getcomposer.org/download/)
- [Docker](https://docs.docker.com/get-docker/)

#### Installation steps
1. Download this repository
2. Install PHP dependencies: `composer install`
3. Install NPM dependencies: `npm install && npm run dev`
4. Copy the environment file `cp .env.example .env`
5. Generate the Laravel Application key: `php artisan key:generate`
6. Build and start the Docker environment: `docker-compose up`
7. Create the database: `docker-compose run app php artisan migrate`

You should now be able to access the site via http://localhost:8080/

## Architecture notes

#### Publication Data Provider Service

The Publication Data Provider service is responsible for retrieving information from the external DOI provider. This
service was designed in order to support easily changing providers in the future. A class contract is used to instantiate
the provider class, and the Service provider fulfills requests for that contract with a concrete CrosRef provider class.
In the future, additional concretions can be developed and substituted in the Service Provider.

This service provider returns results in the form of a standard PublicationResult class. This standardised format allows
other classes to expect a particular format regardless of which providers are used in the future.

#### Publication Cache Service

This service is responsible for retrieving the publication. It first checks the database to see if the publication has
previously been cached, and if so, returns that database record. If not, it then queries the external provider to attempt
to retrieve the publication record. If successful, the record is cached in the database.

This service uses a Laravel Facade in order to simplify mocking in unit tests, and in order to support calling the
primary method without needing to manually instantiate the class.

## Running tests

The simplest way to run unit tests is using the Artisan command in the Docker container:

```
docker-compose run app php artisan test
```

Tests are run against an in-memory SQLite database, which speeds up the tests, and prevents data changes to the application database.

## Future enhancements

* Remove environment variable values from .env.example. I just left them in there for now to simplify setup.
* Handle any other edge cases returned from CrosRef.
* Provide freetext search functionality.
* Make the interface actually look nice.