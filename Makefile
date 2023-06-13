# Initialize variables
ifeq ($(OS),Windows_NT)
currentDir = $(patsubst %/,%, $(subst /mnt, ,$(shell wsl wslpath -u $(strip $(dir $(realpath $(lastword $(MAKEFILE_LIST))))))))
userId = $(shell wsl id -u)
groupId = $(shell wsl id -g)
else
currentDir = $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))
userId = $(shell id -u)
groupId = $(shell id -g)
endif

user = --user $(userId):$(groupId)



# Install PHP Dependencies via Composer
composer-install:
	docker run --rm --name compose-maintainence --interactive \
    --volume $(currentDir):/app \
    $(user) \
    composer:latest install --ignore-platform-reqs --no-scripts

# Install Dev PHP Dependencies via Composer
composer-install-dev:
	docker run --rm --name compose-maintainence-dev --interactive \
    -v $(currentDir):/app \
    $(user) \
    composer:latest install --ignore-platform-reqs --no-scripts --dev

# Update Dev PHP Dependencies via Composer
composer-update:
	docker run --rm --name compose-maintainence-update --interactive \
    --volume $(currentDir):/app \
    $(user) \
    composer:latest update --ignore-platform-reqs --no-scripts

# list Composer outdated direct
composer-outdated-direct:
	docker run --rm --name compose-maintainence-update --interactive \
    --volume $(currentDir):/app \
    $(user) \
    composer:latest outdated -D

# list Composer outdated
composer-outdated:
	docker run --rm --name compose-maintainence-update --interactive \
    --volume $(currentDir):/app \
    $(user) \
    composer:latest outdated

# add PHP Dependencies via Composer - usage make composer-add-dep module=module/namehere
composer-add-dep:
	docker run --rm --name compose-maintainence-update --interactive \
    --volume $(currentDir):/app \
    $(user) \
    composer:latest require $(module) --ignore-platform-reqs --no-scripts

# add Dev PHP Dependencies via Composer - usage make composer-add-dep-dev module=module/namehere
composer-add-dep-dev:
	docker run --rm --name compose-maintainence-update --interactive \
    --volume $(currentDir):/app \
    $(user) \
    composer:latest require $(module) --ignore-platform-reqs --no-scripts --dev
	
#test
test:
	docker run --rm --name test --interactive \
	-v $(currentDir):/app \
    $(user) jitesoft/phpunit:latest /bin/sh -c "cd /app && vendor/bin/phpunit"

