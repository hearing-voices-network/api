# Connecting Voices - API

The main API component for the Connecting Voices platform.

## Getting Started

These instructions will get you a copy of the project up and running on your 
local machine for development and testing purposes. See deployment for notes on 
how to deploy the project on a live system.

### Prerequisites

To run this project, you must have the following installed on your host machine:

* [Docker](https://docs.docker.com/install/)

### The helper script

To abstract the long commands needed to work with Docker Compose, a helper 
script called `develop` has been created at the root of the project. This is 
referenced throughout the rest of this guide and should be used for day-to-day 
tasks when developing.

It essentially just proxies commands to the relevant docker containers. Feel 
free to add commands when necessary.

### Installing

Start by building the Docker image and spinning up the development environment:

```bash
./develop build
./develop up -d
```

At this point you must then download the dependencies and compile the static 
assets:

```bash
# Install dependencies.
./develop composer install
./develop npm install

# Either do a dingle compilation.
./develop npm run dev

# Or, run a watcher for compilation upon file change.
./develop npm run watch
```

Next, configure the environment file:

```bash
# Copy the example file.
cp .env.example .env

# Fill out the details needed.
vim .env
```

Then generate an application key:

```bash
./develop art key:generate
```

Now run the database migrations:

```bash
# Optionally append "--seed" if you want test data to work with.
./develop art migrate [--seed]
```

Create a user for yourself to login with:

```bash
# If you don't specify a password, one will be generated and outputted for you.
./develop art cv:make:admin <name> <email> <phone> [--password=secret] 
```

And finally create OAuth clients for trusted apps:

```bash
# TODO
```

You should now be able to login to the API, and the admin web app once you've 
set it up.

## Running the tests

To run the test suite you can use the following commands:

```bash
# To run both style and unit tests.
composer test

# To run only style tests.
composer test:style

# To run only unit tests.
composer test:unit
```

If you receive any errors from the style tests, you can automatically fix most, 
if not all of the issues with the following command:

```bash
composer fix:style
```

## Deployment

Deployment is all automated through Travis CI. Pushes to the `develop` branch 
will automatically deploy to staging, whereas pushes to `master` will 
automatically deploy to production.

It is important to tag any releases to production using [SemVer](http://semver.org/).

## Built with

* [Laravel 5.8](https://laravel.com/docs/5.8) - The PHP framework used

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of 
conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, 
see the [tags on this repository](https://github.com/hearing-voices-network/api/tags). 

## Authors

* [Ayup Digital](https://ayup.agency)

See also the list of [contributors](https://github.com/hearing-voices-network/api/contributors) 
who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) 
file for details.
