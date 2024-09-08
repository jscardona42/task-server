# Backend (Laravel 10)

## Requisitos previos

- PHP 8.1 o superior
- MySQL 5.7 o superior
- Composer 2.x
- Git

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

5. Copia el archivo `.env.example` a `.env`:
   ```
   cp .env.example .env
   ```

6. Actualiza el archivo `.env` con la configuración de tu base de datos y otras configuraciones necesarias.

7. Genera la clave de la aplicación:
   ```
   php artisan key:generate
   ```

8. Ejecuta las migraciones:
   ```
   php artisan migrate
   ```

9. Ejecuta los scripts de la base de datos:
   Navega a la carpeta `database/scripts/` y ejecuta los scripts en el orden en que se encuentran, uno por uno.

## Configuración

Asegúrate de configurar correctamente los siguientes elementos en tu archivo `.env`:

- `DB_CONNECTION`: mysql
- `DB_HOST`: tu_host_de_base_de_datos
- `DB_PORT`: 3306
- `DB_DATABASE`: inszonedb
- `DB_USERNAME`: tu_usuario_de_mysql
- `DB_PASSWORD`: tu_contraseña_de_mysql

## Ejecución

Para iniciar el servidor de desarrollo, ejecuta:
```
php artisan serve
```

El servidor estará disponible en `http://localhost:8000`.

## Pruebas

Para ejecutar las pruebas, usa el siguiente comando:
```
php artisan test
```

## Estructura del proyecto

- `app/` - Contiene los modelos, controladores y lógica principal
- `database/` - Migraciones y seeders
- `routes/` - Definiciones de rutas
- `tests/` - Pruebas automatizadas
- `config/` - Archivos de configuración

## Solución de problemas comunes

1. Si encuentras errores de permisos, asegúrate de que las carpetas `storage/` y `bootstrap/cache/` sean escribibles por el servidor web.

2. Si las migraciones fallan, verifica la conexión a la base de datos en tu archivo `.env`.

3. Para problemas de dependencias, intenta ejecutar `composer dump-autoload`.

## Contribuir

Si deseas contribuir al proyecto, por favor sigue estas pautas:
1. Haz un fork del repositorio
2. Crea una nueva rama para tu feature
3. Haz tus cambios y commitea
4. Envía un pull request

¡Gracias por contribuir!

## Soporte

Si encuentras algún problema o tienes alguna pregunta, por favor abre un issue en el repositorio de GitHub.

¡Listo! Tu backend de Laravel 10 está configurado y en funcionamiento.
