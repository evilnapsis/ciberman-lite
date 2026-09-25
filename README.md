# Ciberman 2.0

**Ciberman 2.0** es un sistema web moderno para el control, monitoreo y alquiler de computadoras y cabinas en cibercafés, centros de cómputo y salas de videojuegos.

Esta versión ha sido completamente reconstruida y modernizada desde la versión clásica de LegoBox hacia una arquitectura limpia **MVC** con **FastRoute**, plantillas **Twig 3**, capa desacoplada de servicios y persistencia con consultas preparadas PDO.

---

## Características Principales

- **Dashboard en Tiempo Real**: Visualización interactiva del estado de cada cabina/computadora (Disponible en verde, Ocupada en rojo con datos de cliente y hora de inicio).
- **Control de Rentas**: Inicio de sesión de uso con asignación de cliente, equipo y bono en minutos.
- **Cobro Inteligente / Finalización**: Cálculo automático del tiempo transcurrido (minutos/horas), deducción de minutos de cortesía/bono y cálculo de tarifa según precios por hora o fracción.
- **Catálogo de Equipos**: Registro y edición de computadoras, códigos de estación, especificaciones y tarifas por hora y media hora.
- **Historial y Filtros**: Consulta de sesiones históricas por fecha con total recaudado acumulado.
- **Administración de Usuarios**: Roles de Administrador y Operador con gestión de contraseñas.
- **Seguridad**: Ruteo limpio vía FastRoute, validación de tokens CSRF en formularios POST y protección de rutas.

---

## Arquitectura del Proyecto

```
ciberman2/
├── assets/                  # CSS, JavaScript y dependencias frontend (CoreUI, Bootstrap Icons)
├── core/
│   ├── autoload.php         # Autoload de clases base del núcleo LegoBox
│   ├── controller/          # Clases base (Database PDO, Executor, Req, Session, ViewEngine)
│   └── app/
│       ├── autoload.php     # Autoloader PSR-4 para App\Controller y App\Service
│       ├── controller/      # Controladores limpios (AuthController, HomeController, etc.)
│       ├── service/         # Servicios de negocio (EquipmentService, RentService, etc.)
│       ├── model/           # Modelos de BD (EquipmentData, RentData, UserData)
│       └── routes.php       # Enrutador FastRoute centralizado
├── public/
│   ├── auth/                # Plantillas de inicio de sesión
│   ├── equipments/          # Vistas de gestión de equipos
│   ├── home/                # Panel de control y monitor visual
│   ├── layouts/             # Plantilla base Twig con sidebar responsivo
│   ├── profile/             # Perfil de usuario y cambio de contraseña
│   ├── rents/               # Vistas de rentas y cobro de tiempo
│   └── users/               # Vistas de gestión de usuarios
├── index.php                # Front controller principal
├── logout.php               # Cierre de sesión seguro
├── README.md                # Esta documentación
└── schema.sql               # Esquema de la base de datos MySQL
```

---

## Requisitos del Sistema

- PHP 8.0 o superior (compatible con PHP 8.2+)
- MySQL 5.7+ o MariaDB 10.3+
- Extensión PHP PDO y PDO_MySQL
- Servidor web Apache con módulo `mod_rewrite` habilitado

---

## Instalación y Configuración

1. **Base de Datos**:
   - Crea la base de datos `ciberman` en MySQL.
   - Importa el archivo `schema.sql`:
     ```bash
     mysql -u root -p ciberman < schema.sql
     ```
2. **Conexión**:
   - Si requieres cambiar las credenciales de base de datos, edita `core/controller/Database.php`:
     ```php
     $this->user = "root";
     $this->pass = "";
     $this->host = "localhost";
     $this->ddbb = "ciberman";
     ```
3. **Acceso al Sistema**:
   - Abre tu navegador en `http://localhost/update_mv_2026/ciberman2/`
   - Credenciales por defecto:
     - **Usuario**: `admin`
     - **Contraseña**: `admin`

---

## Limpieza de Residuos

Se han removido por completo todos los residuos y artefactos no pertenecientes a este sistema (como ventas comerciales, módulos POS de inventario, proveedores y compras), manteniendo un código conciso, directo y enfocado exclusivamente en la operación de cibercafé.

---

## Créditos y Licencia

- Desarrollado originalmente por **Evilnapsis**.
- Actualizado a la arquitectura moderna LegoBox 2026.