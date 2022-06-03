## Description

<!-- PROJECT LOGO -->
<br />
<p align="center">
  <h3 align="center">Craft API</h3>

  <p align="center">
    A backend application built with Lumen, Docker and PHP
    <br />
    <a href="https://github.com/10deyon/craft_api.git">Report Bug</a>
    ·
    <a href="https://github.com/10deyon/craft_api.git">Request Feature</a>
  </p>
</p>

<!-- TABLE OF CONTENTS -->

## Table of Contents

- [Table of Contents](#table-of-contents)
- [Stack](#stack)
- [Getting Started](#getting-started)
  - [Documentation](#documentation)
  - [Initial setup](#initial-setup)
  - [Running the project](#running-the-project)
- [Authors](#authors)
- [License](#license)

## Stack

This application was built with:

- [Lumen](https://lumen.laravel.com/docs/8.x/installation)
- [Docker](https://www.docker.com/)
- [PHP](https://www.php.net/)

<!-- GETTING STARTED -->

## Getting Started

Before you start, make sure you have [Docker](https://docs.docker.com/install/) and - [Lumen](https://lumen.laravel.com/docs/8.x/installation) installed on your local machine.

### Documentation

Documentation can be found, after initial setup, for HTTP in [Swagger](http://localhost:3000/swagger/#/), for GraphQL in [GraphQL Playground](http://localhost:3000/graphql).

### Initial setup

1. Clone this repo into your local machine

- with **https** </br>
  `git clone https://github.com/10deyon/craft_api.git`
- or with **ssh** </br>
  `git clone git@github.com:10deyon/craft_api.git`

2. Launch Docker compose to run MySQL's images.
   `docker compose up -d`
   
3. Open API container's terminal
   `docker ps` - copy container ID
   `docker compose exec -it "container_id" bash`

4. Deploy database schema into the MySQL database.
   `php artisan migrate`

5. Seed the database with default data.
   `no seed available for the project`

### Running the project

After the initial setup there's no additional work needed, project is running in the background as a Docker container.

- The REST API is available on your local machine on `http://localhost:8080`.

You can stop it by executing `docker compose stop`, and you can resume it by `docker compose start`
  * if any change(s) is made, run the following
      `docker-compose down`
      `docker-compose build`
      `docker compose up -d`


<!-- Authors -->
## Authors

1. <a href="https://github.com/10deyon" target="_blank">Emmanuel Deyon, Avoseh</a>
