docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-build:
	docker-compose up --build -d

migrate:
	docker-compose exec php-cli php artisan migrate

db-refresh:
	docker-compose exec php-cli php artisan migrate:refresh --seed

test:
	@docker-compose exec php-cli vendor/bin/phpunit

assets-install:
	docker-compose exec node yarn install

assets-dev:
	docker-compose exec node yarn dev

assets-watch:
	docker-compose exec node yarn watch

perm:
	sudo chown ${USER}:${USER} bootstrap/cache -R
	sudo chown ${USER}:${USER} storage -R
#	if [-d "node_modules"]; then sudo chown ${USER}:${USER} node_modules -R; fi
#	if [-d "public"]; then sudo chown ${USER}:${USER} public -R; fi