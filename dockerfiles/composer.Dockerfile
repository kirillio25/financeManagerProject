FROM composer:latest 

WORKDIR /var/www/laravel

ENTRYPOINT ["composer", "--ignore-platform-reqs"] 
# --ignore-platform-reqs  ждет команду от нас