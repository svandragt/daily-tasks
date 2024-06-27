FROM jetpackio/devbox-root-user:latest

WORKDIR /code
COPY . /code

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN devbox run composer install

EXPOSE 9082

CMD ["devbox", "run", "composer", "run", "serve"]
