NFQ Test
========

#Requirements:
1. Composer
2. Docker (optional)

#Quick setup
1. Clone the git repository
2. `cd` to `/your/projects/nfqtest`
3. `composer install`
4. `php bin/symfony_requirements` and install all the missing libraries it complains about.
5. `docker-compose build`
6. `docker-compose up -d`
7. If database port is exposed as 3307, than login into a container `docker exec -it YOUR_PHP_CONTAINER_NAME bash`
8. Copy `app/config/parameters.yml.dist` to `app/config/parameters.yml` and fill in your local settings.
9. `php bin/console doctrine:database:create`
10. `php bin/console doctrine:schema:update --force`
11. `php bin/console doctrine:fixtures:load`

#Default admin logins
admin / admin
