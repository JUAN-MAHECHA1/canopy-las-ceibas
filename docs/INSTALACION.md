# Canopy Las Ceibas — Guía de instalación

## 1. Requisitos
- PHP >= 8.2, Composer, MySQL/MariaDB, Node.js (para compilar Tailwind en producción)

## 2. Crear el proyecto base
```bash
composer create-project laravel/laravel canopy-las-ceibas
cd canopy-las-ceibas
composer require laravel/breeze --dev
php artisan breeze:install blade   # instala login/registro/auth scaffolding
```

## 3. Copiar los archivos de este paquete
Copia el contenido de `app/`, `database/`, `routes/web.php`, `config/canopy.php` y
`resources/views/` de este paquete DENTRO de tu proyecto Laravel recién creado,
respetando las mismas rutas (sobrescribe `routes/web.php`, que ya incluye
`require __DIR__.'/auth.php'` generado por Breeze).

## 4. Registrar el middleware de roles
Abre `bootstrap/app.php` y agrega el bloque que está en
`docs/bootstrap-app-middleware.php` dentro de `->withMiddleware(...)`.

## 5. Configurar .env
```
DB_DATABASE=canopy_las_ceibas
DB_USERNAME=...
DB_PASSWORD=...
CANOPY_VALOR_POR_PERSONA=10000
```

## 6. Migrar y sembrar datos
```bash
php artisan migrate
php artisan db:seed
```
Esto crea:
- 1 admin: admin@canopylasceibas.com
- 4 jefes (uno por empresa): jefe.utica.xtrema@canopylasceibas.com, etc.
- 4 guías: juan.pablo.beltrán@canopylasceibas.com, etc. (Juan Pablo queda como líder por defecto — cámbialo en el seeder si es otro)
- Contraseña de todos: `password` (cámbiala en producción)

## 7. Levantar el proyecto
```bash
php artisan serve
```

## 8. Flujo de uso diario
1. El guía líder entra a `/lider/turno`, abre el turno y marca qué guías están activos hoy.
2. Los guías activos entran a `/guia/registro-clientes` y registran cada reserva
   (por nombre o por código, según la empresa).
3. Los jefes ven `/jefe/dashboard`: tabla comparativa Empresa x Día (igual a su Excel)
   y totales en dinero por empresa.
4. El admin ve `/admin/dashboard`: pagos totales agrupados por semana.

## Liderazgo rotativo
El liderazgo NO es fijo: cualquier guía puede ser líder, pero solo uno a la vez
(`LeadershipService` lo garantiza). Dos formas de cambiarlo:
1. El líder actual, desde `/lider/turno`, transfiere el liderazgo a otro guía.
2. Un admin, desde `/admin/guias`, puede asignarlo directamente (útil si el líder
   actual no puede entrar ese día).
El seeder deja a un guía como líder inicial solo para que el sistema arranque
operando desde el día 1; cámbialo cuando quieras desde cualquiera de esas dos pantallas.

## Módulo de novedades
- El guía reporta desde `/guia/novedades` (tipo: cliente, equipo o incidente).
- El admin las revisa todas, con filtro por tipo, en `/admin/novedades`.
- Cada novedad queda ligada al `work_day` del momento en que se reportó (si había
  turno abierto), para poder cruzarla después con quién estaba de turno ese día.

## Pendientes a definir contigo antes de producción
- Lista real de festivos colombianos año a año (`config/canopy.php`).
- Si los JEFES también deben poder ver las novedades de "incidentes" de su propia
  empresa (hoy solo las ve el admin).
- Compilar Tailwind con Vite en vez del CDN usado en `layouts/app.blade.php`
  (el CDN es solo para desarrollo/prototipo rápido, no para producción).

## PWA (instalar como app en el celular)
El proyecto ya trae lo necesario para que el navegador ofrezca "Instalar app" /
"Agregar a pantalla de inicio":
- `public/manifest.json`: nombre, colores e íconos de la app.
- `public/sw.js`: Service Worker que cachea CSS/imágenes para que cargue rápido.
- `public/icons/icon-192.png` y `icon-512.png`: íconos de ejemplo (cámbialos por
  el logo real de Canopy Las Ceibas cuando lo tengas — mismas medidas, mismo nombre).
- Enlazado en `resources/views/layouts/app.blade.php`.

**Importante:** los navegadores solo permiten instalar una PWA si el sitio corre
bajo **HTTPS** (o `localhost` para pruebas). En XAMPP local con `http://localhost`
va a funcionar para probar cómo se ve/instala; para que tus guías la instalen de
verdad en sus celulares en el sitio de trabajo, el proyecto debe estar publicado
en un hosting con HTTPS.

No se implementó guardado offline de registros de clientes a propósito: las
reglas de negocio (turno abierto, guía activo, horario permitido) se validan en
el servidor, y guardar sin conexión requeriría sincronizar después con riesgo de
duplicados. Si más adelante ven que la señal falla seguido en el sitio, se puede
agregar esa capa en una siguiente iteración.
