FROM php:8-cli-alpine3.12

COPY . usr/src/app
WORKDIR /usr/src/app
CMD [ "php", "index.php" ]
