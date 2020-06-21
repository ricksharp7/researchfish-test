# ResearchFish Code Challenge Submission by Rick Sharp

## Setting up a local environment

#### Requirements

-   [Composer](https://getcomposer.org/download/)
-   [Docker](https://docs.docker.com/get-docker/)

#### Installation steps

1. Clone this repository
2. Install PHP dependencies: `composer install`
3. Install NPM dependencies: `npm install && npm run dev`
4. Copy the environment file `cp .env.example .env`
5. Generate the Laravel Application key: `php artisan key:generate`
6. Build and start the Docker environment: `docker-compose up`
7. Create the database: `docker-compose run app php artisan migrate --seed`

You should now be able to access the site via http://localhost:8080/

## Partial searches

The project spec called for support of partial DOIs. However, the CrosRef documentation does not list any support for partial DOI searching. For this project, I implemented a wildcard search in the database, with wildcards placed at the beginning and end of the search term. So a search for "a0030" will match the document with the DOI of "10.1037/a0030689" in the database. 

The database migration script will create a number of records with DOIs that start with "10.1037/".

## Architecture notes

#### Publication Data Provider Service

The Publication Data Provider service is responsible for retrieving information from the external DOI provider. This
service was designed in order to support easily changing providers in the future. A [class contract](https://github.com/ricksharp7/researchfish-test/blob/master/app/Services/PublicationDataProvider/Contracts/DataProvider.php) is used to instantiate
the provider class, and the [Service Provider](https://github.com/ricksharp7/researchfish-test/blob/master/app/Providers/PublicationDataProvider.php) fulfills requests for that contract with a concrete [CrosRef provider class](https://github.com/ricksharp7/researchfish-test/blob/master/app/Services/PublicationDataProvider/Providers/CrosRef.php).
In the future, additional concretions can be developed and substituted in the Service Provider.

This service returns results in the form of a standard [PublicationResult](https://github.com/ricksharp7/researchfish-test/blob/master/app/Services/PublicationDataProvider/PublicationResult.php) class. This standardised format allows
other classes to expect a particular format regardless of which providers are used in the future.

#### Publication Cache Service

[This service](https://github.com/ricksharp7/researchfish-test/blob/master/app/Services/PublicationCacheService.php) is responsible for retrieving the publication. It first checks the database to see if the publication has
previously been cached, and if so, returns that database record. If not, it then queries the external provider to attempt
to retrieve the publication record. If successful, the record is cached in the database.

This service uses a [Facade](https://github.com/ricksharp7/researchfish-test/blob/master/app/Facades/PublicationCacheFacade.php) in order to simplify mocking in unit tests, and in order to support calling the
primary method without needing to manually instantiate the class.

## Running tests

The simplest way to run unit tests is using the Artisan command in the Docker container:

```
docker-compose run app php artisan test
```

Tests are run against an in-memory SQLite database, which speeds up the tests, and prevents data changes to the application database.

## Future enhancements

- Remove environment variable values from .env.example. I just left them in there for now to simplify setup.
- Support publisher (prefix) searches with CrosRef
- Handle any other edge cases returned from CrosRef.
- Add validation for the DOI format.
- Support results pagination.
- Support free text search functionality.
- Make the interface actually look nice.
