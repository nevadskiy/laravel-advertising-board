up:
	docker-compose up -d

down:
	docker-compose down

docker-build: memory
	docker-compose up --build -d

migrate:
	docker-compose exec php-cli php artisan migrate

db-refresh:
	docker-compose exec php-cli php artisan migrate:refresh --seed

test:
	@docker-compose exec php-cli vendor/bin/phpunit

status:
	docker-compose ps

# Install node assets
assets:
	docker-compose exec node yarn install

# Run front-end assets watch-builder
watch:
	docker-compose exec node yarn watch

queue:
	docker-compose exec php-cli php artisan queue:work

# Memory for virtual container (Elasticsearch depends on it)
memory:
	sudo sysctl -w vm.max_map_count=262144

perm:
	sudo chown ${USER}:${USER} app -R
	sudo chmod -R 777 bootstrap/cache
	sudo chmod -R 777 storage
	sudo chown ${USER}:${USER} bootstrap/cache -R
	sudo chown ${USER}:${USER} storage -R
#	if [-d "node_modules"]; then sudo chown ${USER}:${USER} node_modules -R; fi
#	if [-d "public"]; then sudo chown ${USER}:${USER} public -R; fi