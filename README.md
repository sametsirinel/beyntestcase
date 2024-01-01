# İhtiyaçlar 

- Docker 

## Kurulum 
##### kurulum için aşağıdaki kodları sırayı ile uygulayın

``` sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
``` sh
cp .env.example .env
```
``` sh
alias sail="./vendor/bin/sail"
```
``` sh
sail up
```
``` sh
sail artisan migrate:fresh --seed
```

[Postman Collection](./postman-collection.json)
