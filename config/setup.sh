#!/bin/bash
#mysql -u root < setup.sql
php setup.php
chmod 777 ../photoStorage
## Рекурсивное копирование из текущей папки в папку на сервере
#scp -r . Server:/home/bsabre/Documents/Camagru
