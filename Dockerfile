FROM php:8.2-cli-alpine

RUN apk add --no-cache \
    bash \
    git \
    icu-dev \
    libzip-dev \
    zlib-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql intl zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . .

CMD sh -lc "composer install \
  && (test -f .env || cp .env.example .env) \
  && (php -r 'exit((int) (trim((string) getenv("APP_KEY")) !== ""));' || php artisan key:generate --force) \
  && (test -L public/storage || php artisan storage:link || true) \
  && (test -d node_modules || npm install) \
  && (test -d public/build || npm run build) \
  && php artisan serve --host=0.0.0.0 --port=8000"
