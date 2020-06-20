# Rick Sharp Code Challenge Submission

## Setting up a local environment

### Requirements
- [Composer](https://getcomposer.org/download/)
- [Docker](https://docs.docker.com/get-docker/)

### Installation steps
1. Download this repository
2. Install dependencies: `composer install`
3. Copy the environment file `cp .env.example .env`
4. Generate the Laravel Application key: `php artisan key:generate`
5. Build and start the Docker environment: `docker-compose up`

You should now be able to access the site via http://localhost:8080/

## Future enhancements

* Remove environment variable values from .env.example. I just left them in there for now to simplify setup.
* Handle any other edge cases returned from CrosRef
* Provide freetext search functionality