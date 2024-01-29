## Running locally

- build and run app:
    `docker-compose-php up -d`
- make storage:link
    `docker exec app php artisan storage:link`
- migration 
    `docker exec app php artisan migrate --seed`

- visit folder `_pastman` to request collections. localURL = http://localhost