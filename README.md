# Canopy Las Ceibas

Sistema de gestión para la operación de canopy (tirolesa) de "Canopy Las Ceibas":
registro de clientes por empresa, control de turnos con liderazgo rotativo,
cálculo automático de pagos a guías, reportes para los jefes de cada empresa
y reporte de novedades.

## Estado del proyecto
Este repositorio contiene el **código de dominio** (modelos, migraciones,
servicios, controladores, vistas Blade) pensado para copiarse dentro de un
proyecto Laravel recién creado con Breeze. Instrucciones completas de
instalación paso a paso en [`docs/INSTALACION.md`](docs/INSTALACION.md).

## Estructura principal
```
app/
  Models/        -> Empresa, User, WorkDay, WorkDayGuide, RegistroCliente, Novedad
  Services/      -> PaymentService, WorkDayService, ScheduleValidationService, LeadershipService
  Http/
    Controllers/ -> separados por rol: Admin, Jefe, Guia, Lider
    Requests/    -> FormRequests de validación
    Middleware/  -> CheckRole (admin / jefe / guia / lider)
  Rules/         -> HorarioPermitidoRule (fines de semana y festivos 9am-6pm)
database/
  migrations/
  seeders/       -> datos reales: 4 empresas, 1 admin, 4 jefes, 4 guías
resources/views/ -> Blade + Tailwind, por rol
routes/web.php
config/canopy.php -> valor por persona, horario restringido, festivos
docs/             -> guía de instalación y fragmento de bootstrap/app.php
```

## Roles
- **admin**: ve pagos semanales totales, gestiona guías y el liderazgo, ve novedades.
- **jefe**: ve el reporte comparativo entre empresas (matriz Empresa x Día).
- **guia**: registra clientes, ve su ganancia semanal, reporta novedades.
- **líder** (no es un rol aparte, es un guía con `es_lider = true`, rota entre los 4):
  abre el turno del día, marca guías activos y puede transferir el liderazgo.

## Licencia
Privado — uso interno de Canopy Las Ceibas.
