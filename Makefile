build:
    docker compose build --force-rm

down:
    docker compose down

download-empty-project:
    docker compose exec symfony composer create-project symfony/skeleton:"7.2.*" .
    docker compose exec symfony composer require webapp

up:
    docker compose up -d --force-recreate --remove-orphans

remove-sources:
    rm -Rf crud_symfony && mkdir crud_symfony

install: remove-sources down up download-empty-project

start:
    docker compose up -d --no-recreate --remove-orphans

stop:
    docker compose stop

enter:
    docker compose exec symfony bash

restart-nginx:
    docker compose exec nginx nginx -s reload

load-fixtures:
    docker compose exec symfony php bin/console doctrine:fixtures:load