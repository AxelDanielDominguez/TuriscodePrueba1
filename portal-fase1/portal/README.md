# El Portal de la Quebrada — sitio web

PHP 8.0+ sin base de datos. Los datos editables viven en `data/*.json`.

## Probar en local (Windows + Laragon)
1. Instalar Laragon (`winget install Laragon.Laragon`) y abrirlo.
2. Botón **Terminal** de Laragon (ya trae `php`). Ir a la carpeta del proyecto.
3. Descargar las imágenes originales: `powershell -ExecutionPolicy Bypass -File scripts\descargar-imagenes.ps1`
4. Levantar el sitio: `php -S localhost:8000 -t public public/index.php`
5. Abrir http://localhost:8000 y http://localhost:8000/en/

## Estructura
- `public/` única carpeta pública (index.php es el router de todo el sitio).
- `app/` lógica: `helpers.php` (funciones únicas), `routes.php` (URLs), `views/` (HTML).
- `data/` contenido editable (se protege del acceso web).
- `docs/MANTENIMIENTO.md` qué tocar y cuándo.
