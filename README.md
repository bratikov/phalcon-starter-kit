# Phalcon REST API Starter Kit

## About the project

The project was created to quick deploy a ready-made Phalcon framework environment for writing REST API applications. It includes an example codebase to start the application, as well as:
+ A minimally necessary set of Docker containers to start the application;
+ Utilities for configuring, building, and deploying the application;
+ Utilities for generating table models and database migrations;
+ PHP dev-tools such as PHPCS, PHPStan, PHPUnit;
+ The ability to log errors and exceptions in Sentry;
+ Examples of documenting API endpoints, an OpenAPI standard documentation generator, collections and environments for Hoppscotch.

## What is it for?
When developing services based on REST API, we often face the same routine tasks. Installing software, choosing a framework, setting up routing, installing and configuring basic developer utilities, setting up code documentation tools, and much more. This solution is offered to simplify this process. The main task is to create your project in a few minutes, based on this one, and focus on implementing business logic, adding missing components to the system, without spending time on implementing basic functionality.

## Installation and setup

### System requirements
To run and work with the project, you need to install **docker** and the **docker-compose** plugin. For installation on Linux, follow the [instructions](https://docs.docker.com/engine/install/). For local installation on your PC, you can use [Docker Desktop](https://www.docker.com/products/docker-desktop/), available for all OS.

### Quick configuration and installation
1. Clone this base project into a new project in any way convenient for you.
2. Run the `configure` script and answer for prompted questions.
3. Run the `build` script.
4. Check application components:
   + http://localhost:50110 - the application is deployed here, you can check its availability by opening the basic [endpoint](http://127.0.0.1:50110/v1/dummy)
   + http://localhost:50111 - PHPMyAdmin is deployed here for quick access to the local MySQL database. Check the `.env` file for credentials.
   + http://localhost:50112 - Swagger is deployed here to view the API methods documentation.

## Application Components
### Structure
The root of the application contains configuration and launch scripts, CI/CD job descriptions (GitLab ready only), and a docker-compose.yml file describing the containers for the build. Below is a complete list of (sub)directories and their functional purposes:
+ **app** - main application directory
  + **config** - contains all configuration files, both for the application itself and for additional utilities (PHPCS, PHPStan), as well as `loader.php` for loading all necessary dependencies
  + **docs** - contains OpenAPI documentation files, as well as working collections and environments for Hoppscotch
  + **migrations** - migrations directory, all created migrations are located here
  + **public** - public entry point (document_root) of the application
  + **src** - application source code directory. Here are the basic classes that implement the API. Implement your application logic here as well. Includes:
    + `Endpoints` - API methods, implement your own here
    + `Models` - stores generated table models
    + `Tasks` - directory for tasks (cron tasks)
    + `Utils` - code for additional system components
  + **tests** - write Unit tests here
+ **bin** - directory for additional bash scripts, used both for configuring the application itself and for running utilities. More details in the Utilities section.
+ **docker** - folder for container configurations, in case the basic functionality of the images is not enough.
+ **volumes** - contains mounted container volumes

### Containers
The project includes (in my opinion) only the basic containers necessary to run a basic API-based application. You always have the option to extend your application with the functionality required to implement specific business logic. All of them are described in the docker-compose.yml file. Below is a list of services and a brief description of their purpose:
+ **mysql** - MySQL database, persistent storage is mounted to the `volumes` folder
+ **fpm** - PHP-FPM based on a custom image (PHP8.3) that includes Phalcon (version 5.8 at the time of writing), pdo_mysql, redis, swoole, decimal, and basic extensions. You can browse images repository and find images for lower PHP version as well - https://hub.docker.com/r/bratikov/php/tags. If something is missing, you can install it by extending the base image (a slow method for updating and rebuilding) or by building your own image.
+ **nginx** - internal proxy service for fpm
+ **redis** - Redis server, it could have been excluded from the basic build, but it is very often used. Persistent storage is also mounted to `volumes`
+ **pma** - PHPMyAdmin comes out of the box for convenient database viewing
+ **swagger** - UI for viewing the generated API documentation of the project
### Utilities
The project already includes bash utilities for convenient configuration of the application itself, as well as for running individual functional components of the system. They are located in the `bin` folder. They are often used daily, so below is a brief overview:
+ **env** - system configuration script, should not be run directly
+ **gendocs** - script for generating documentation based on annotations in the code. If you have written new API methods, added/changed method annotations, you need to run this script to regenerate the documentation. JSON files will be regenerated and can be found in the `app/docs` folder
+ **goin** - alias for quickly entering a container. Example: `./bin/goin fpm` - will start sh in the `fpm` container
+ **migration** - script for generating database migrations. Phalcon supports a so-called Reverse Engineering approach. The principle is simple: every time you want to add or change the current database schema, you do it manually (through PMA or another convenient tool), and then run the migration generation for your changes. Example: we want to add a new table `test`. Create it in your environment, run `migration generate test` - get a ready migration that can be applied again in your environment and will be applied by all other developers or in other environments (stage, prod, whatever). This approach also applies to changing the structure of existing tables.
+ **misc** - system configuration script, does not carry any useful load when run directly (used in other scripts)
+ **model** - script for generating PHP models for existing tables. Works on the same principle as migrations. Create a table, create a migration, create a model `model test`. In the `app/src/Models` folder, a table model class will be created.
+ **phpcs** - script for manually checking code style. It not only checks but also immediately fixes all found errors. Works in two modes: a) without parameters, it searches for modified project files using `git diff` and processes them; b) you can also specify a path to specific file(s) and fix only them. The standards configuration is located in `app/config/csfixer.php`. You can use your own if desired. You can set up auto-fix in the development environment when saving files. On push, if the code style are not met, the CI code quality test will fail.
+ **phpstan** - script for manually running the static code analyzer. PHPStan is used with a check level of 9 (maximum level is 10). It runs in the same way as `phpcs`, and on push with errors, the CI test will fail. It is recommended to run both phpcs and phpstan before each commit/push.
+ **phpunit** - script for running unit tests, also included in the basic CI tests.
+ **runtask** - script for running a specific task from the collection in the `app/src/Tasks` folder. Example: `exectask main hello`. In this case, `MainTask::helloAction()` will be run.
### Basic Principles for Implementing Your Own Application
The base project is a stub, so you can modify it as you wish. However, some principles were laid down during its creation, which should (can) be followed when extending it for your needs:
1. Add your code to `app/src`, create your own namespaces there, and use them in the final API methods `app/src/Endpoints`
2. The API out of the box supports versioning, which may or may not be needed, but just in case.
3. Feel free to add the necessary services for the application to work. `docker-compose.yml` - everything goes there, if customization is needed, create the corresponding folders in `docker`.
4. Extend environment variables for your needs, as well as the application configuration itself. The following principle applies here. The project has 2 template files: `.env.local` and `app/config/sample.app.json`. The `.env.local` file contains default environment variables, such as database access, timezone, etc. Based on it, a working `.env` file is generated, which is used to build the containers. If you want to extend the basic configuration, extend `env.local`, run `build` to rebuild, and everything will be in the working `.env`. `app/config/sample.app.json` works in the same way, if you want to extend the basic application configuration, extend it immediately. After rebuilding, all necessary parameters will be added to the working application configuration.
5. Add new jobs to `gitlab-ci.yml`. By default, there are 3 test jobs: phpcs, phpstan, and phpunit, which run on every push to the repository (except for production branches). Need automatic deployment - add it there, need other tests - add them there too.
## FAQ
+ _**Will the Debugger work?**_
+ Yes, but with environment limitations. The build includes xdebug, you can configure it in the `docker/fpm/app.ini` file.
  + In local development (docker root mode), there are no restrictions, access to the localhost port of the host machine is done through the basic docker gateway.
  + In development on a dev server in docker rootless mode, this is not possible, with one caveat - configure the IDE port to open the external interface port, and in xdebug configure the port listening to the external IP. This solution is highly discouraged.
+ _**Can I deploy multiple projects in one environment?**_
+ Yes, you can. To do this, you need to change the network data (container IP addresses, subnet, and gateway, as well as container names). Also, the ports forwarded to the host should not overlap. To do this, you need to change the `APP_DPORT`, `PMA_DPORT`, `DOC_DPORT` parameters in the environment file. Just set them different from the existing ones. These are the so-called destination ports of the host system. If you look at `docker-compose.yml`, they are formed as follows: 5 + ${SUID} + {DPORT}. Where 5 is an arbitrarily chosen high port number, SUID is the last 2 digits of your system user ID, and DPORT is just a random set of two-digit numbers. Why all this? So that in the dev environment, the ports of different developers do not overlap.
+ _**Ok, I deployed the application, tested it, wrote my API methods, and want to deploy to production. How?**_
+ Let's agree that the production environment has target=prod. Therefore, we need to configure and set it up on the production server as described at the beginning of the manual. But before that, a couple of manipulations are necessary:
  1. Copy `docker-compose.yml` to `docker-compose.prod.yml`. Docker works on the principle of override, i.e., in the production config, we can either completely change the sets of services or partially change (modify part of the service declaration)
  2. Copy `.env.local` to `.env.prod`. Change the accesses, specify the necessary hosts, etc.
  3. Then run `./configure prod` and then `build`. Read more in the "Installation and setup" section.