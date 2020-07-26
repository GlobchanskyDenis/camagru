#!/bin/bash/
## Удаление содержимого папки проекта на сервере
ssh Server 'bash -s' < deploy_helper.sh
## Копирование всех файлов и папок из текущей папки в папку проекта на сервере
scp -r * Server:/home/bsabre/Documents/Camagru
