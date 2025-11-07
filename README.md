## Realtime game application

# Installation
1. Clone the repository:

2. go to the project directory

3. Run `docker compose run app composer install`.

4. Run `cp .env.example .env`.

5. Run `docker network create sheypoor_network`.

6. Run `docker compose up -d`.

7. Run `docker exec -it sheypoor_app php artisan app:deploy`.

8. Access app on `http://localhost:8000`.

# API Testing with Postman
1. Import the Postman Sheypoor.postman_collection.json collection.
2. Run `docker exec sheypoor_app php artisan db:seed`
3. To Keep sync between database and redis run `docker exec -it sheypoor_app  php artisan queue:work`.

# API endpoints:
    - GET api/users/: get top n ranked users
    - POST api/users: create new user
    - GET api/users/{userId}: get user data consist of score and rand
    - PUT api/users/{userId}: update user score

# Technologies used:
- Laravel Framework 12.37.0
- Docker
- Redis
- Mysql
- frankenphp

# Automated Tests
To run the automated tests, use the following command:
```
docker compose run app php artisan test
```

project contains unit and feature tests.

