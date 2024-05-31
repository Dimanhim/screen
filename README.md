Развернуть проект
1) создаем базу данных на хостинге
2) Клонируем репозиторий 
   git clone https://git.madeformed.ru/MadeForMed/Screens.git <site_folder>
   Нужно будет ввести логин и пароль от аккаунта git.madeformed.ru
3) для корректной работы версия php должна быть 7.4
4) переходим в папку с сайтом, разворачиваем проект
   php7.4 /usr/local/bin/composer update
5) Инициализация проекта
   php7.4 init
6) Выбираем Development, ОТКАЗЫВАЕМСЯ перезаписать файлы backend/web/index.php и frontend/web/index.php
7) в корне создаем файл .env, копируем в него содержимое .env.example и прописываем данные для подключения к МИС
8) прописываем на хостинге путь к первой исполняемой директории <domain_name>/backend/web

  
   
 

