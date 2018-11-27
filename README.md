symfony-shop
===========

Учебный проект интернет-магазина на Symfony 3.4

### Установка приложения
1. Произвести клонирование репозитория на локальную машину:

        git clone https://github.com/zaitcev-ivan/symfony-project.git

2. Перейти в директорию с проектом:

        cd symfony-project

3. Установить зависимости composer:
    
        composer install
    
    При создании локального файла app/config/parameters.yml установить параметры:
    
        database_host: db
        database_port: 3306
        database_name: mydb
        database_user: user
        database_password: userpass
        mailer_transport: smtp
        mailer_host: 127.0.0.1
        mailer_user: null
        mailer_password: null
        secret: сгенерировать (нажать Enter)
        passwordResetTokenExpire: 3600
        app.notifications.email_sender: anonymous@example.com
        elastic_host: elasticsearch
        elastic_port: 9200
        
4. Скопировать файл .env.dist в локальный .env
    
    В локальном файле .env установить параметры:
    
        SYMFONY_APP_PATH=./
        MYSQL_ROOT_PASSWORD=root
        MYSQL_DATABASE=mydb
        MYSQL_USER=user
        MYSQL_PASSWORD=userpass
        TIMEZONE=Europe/Moscow
        
5. Скопировать файл phpunit.xml.dist в локальный phpunit.xml
        
6. Запустить установку docker-контейнеров:
    
        docker-compose up --build -d
    
7. Перейти в контейнер `php-fpm`:
       
           docker-compose exec php-fpm bash
           
8. Запустить миграции:

        php bin/console doctrine:migrations:migrate

9. Запустить обновление фикстур:
        
        php bin/console doctrine:fixtures:load

7. Запустить проверку unit-тестов:

        ./vendor/bin/phpunit