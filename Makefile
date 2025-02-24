# Имя контейнера Laravel
APP_CONTAINER=app

# Команды для APP_CONTAINER

build:
	docker-compose up -d --build

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose down && docker-compose up -d

logs:
	docker-compose logs -f $(APP_CONTAINER)

migrate:
	docker exec -it $(APP_CONTAINER) php artisan migrate --force

seed:
	docker exec -it $(APP_CONTAINER) php artisan db:seed --force

migrate-fresh:
	docker exec -it $(APP_CONTAINER) php artisan migrate:fresh --seed

key-generate:
	docker exec -it $(APP_CONTAINER) php artisan key:generate

jwt-secret:
	docker exec -it $(APP_CONTAINER) php artisan jwt:secret

cache-clear:
	docker exec -it $(APP_CONTAINER) php artisan cache:clear
	docker exec -it $(APP_CONTAINER) php artisan config:clear
	docker exec -it $(APP_CONTAINER) php artisan config:cache

route-list:
	docker exec -it $(APP_CONTAINER) php artisan route:list

tinker:
	docker exec -it $(APP_CONTAINER) php artisan tinker

bash:
	docker exec -it $(APP_CONTAINER) bash

psql:
	docker exec -it postgres psql -U postgres -d app

# Команды Elasticsearch
es-health:
	curl -X GET "localhost:9200/_cluster/health?pretty"
