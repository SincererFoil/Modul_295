XAMPP Installation:





1. C:\\xampp\\htdocs -> cmd -> git clone https://github.com/SincererFoil/Modul\_295



2\. composer install



3 .C:\\xampp\\apache\\conf\\httpd.conf :



DocumentRoot "C:/xampp/htdocs/Modul\_295/public"

<Directory "C:/xampp/htdocs/Modul\_295/public">









Docker Installation:



composer install



docker compose build



docker compose up





Import Database:



docker compose exec -T mysql mysql -u root uek295 < uek295.sql

