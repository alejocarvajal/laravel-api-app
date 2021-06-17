<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


## Instalación

- Requiere PHP 7.2

- Descomprimir el archivo stradataAPI.zip
- Acceder a la carpeta stradataAPP
- Modificar el archivo .env los parametros de la BD
    ~~~
    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=stradata
    DB_USERNAME=root
    DB_PASSWORD=admin
    ~~~
- Importar el archivo stradata.sql en la base de datos

## Ejecución
- A través del terminal se ejecuta el comando:

    - php artisan serve

 - Una vez ejecutado, ingresar a http://localhost:8000

- En la pantalla inicial solicitará un email y clave, el usuario creado por defecto es

    - email: test@gmail.com
    - password: 12345678

- Una vez se ingrese exitosamente solicitará el nombre a buscar y el porcentaje de coincidencia.
