# Backend (Laravel 10)

## Requisitos previos

- PHP 8.1 o superior
- MySQL
- Composer

## Instalación

1. Clona el repositorio:
   ```
   git clone https://github.com/jscardona42/task-server.git
   ```

2. Navega al directorio del proyecto:
   ```
   cd task-server
   ```

3. Instala las dependencias:
   ```
   composer install
   ```

4. Crea una base de datos llamada `inszonedb` en MySQL.

5. Copia el contenido del archivo `.env` proporcionado y reemplaza el contenido del archivo `.env` en tu proyecto.

6. Ejecuta las migraciones:
   ```
   php artisan migrate
   ```

7. Ejecuta los scripts de la base de datos:
   Navega a la carpeta `database/scripts/` y ejecuta los scripts en el orden en que se encuentran, uno por uno.

## Ejecución

Para iniciar el servidor de desarrollo, ejecuta:
```
php artisan serve
```

El servidor estará disponible en `http://localhost:8000` por defecto.

¡Listo! Tu backend de Laravel 10 está configurado y en funcionamiento.
