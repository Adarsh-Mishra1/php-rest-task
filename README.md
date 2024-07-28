Recipe Management API

This API allows you to manage recipes and their ratings using a simple RESTful interface. It is built using PHP and MySQL, with the application running inside Docker containers.

To run this application you need docker installed in your system.
  Create the required files like compose and docker file or you can simply take a pull request from this repository
  it will be fully function with docker in working condtion.

  Steps to run this application:
    Setup docker
    Take pull request .
    Use command docker-compose build
    then command docker-compose run
    modify or use the ports as per your need.
  
Features

    Create, Read, Update, and Delete (CRUD) Recipes: Manage your recipes with ease using the endpoints provided.
    Rate Recipes: Rate your recipes and store ratings in the database.
    List All Recipes: Retrieve a list of all recipes stored in the database.
    Authentication: Secure actions like create, update, delete, and rate using a simple token-based authentication.

Endpoints

    POST /?action=create: Create a new recipe.
    PUT /?action=update&id={id}: Update an existing recipe.
    DELETE /?action=delete&id={id}: Delete a recipe.
    POST /?action=rate&id={id}: Rate a recipe.
    GET /?action=list: List all recipes.
    GET /?action=get&id={id}: Get details of a specific recipe.

Authentication

For actions like create, update, delete, and rate, an authorization token is required. Include the token in the Authorization header as follows:

makefile

Authorization: Bearer token24810

Setup

    Docker Setup:
        Ensure you have Docker installed.
        Create a docker-compose.yml file with the provided configuration.
        Run docker-compose up to start the services.

    MySQL Configuration:
        Database Name: recipe_db
        User: myuser
        Password: mypassword
        Root Password: rootpassword

    Access PHPMyAdmin:
        Visit http://localhost:8080 to manage your MySQL database using PHPMyAdmin.
        Login with root and rootpassword.

Directory Structure

    ./php: Contains the PHP application code.
    ./mysql/my.cnf: MySQL configuration file.
    ./phpmyadmin: Contains PHPMyAdmin configuration
