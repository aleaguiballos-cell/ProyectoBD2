# Sistema Integral de Recepción y Administración — Carnes Ideal

**Estudiante:** Alejandro Aguilera Ceballos  
**Matrícula:** 2025630009  
**Asignatura:** Bases de Datos 2026-2  
**URL del sistema:** https://carnicerialaidealescom1.page.gd/CARNES/Login.html

---

## Descripción del proyecto

Sistema web de registro y control de recepción de productos para la empresa **Carnes Ideal**. Permite registrar la entrada de mercancía de proveedores con características sensoriales, temperatura, empaques y evidencias. Cuenta con autenticación de usuarios, roles (admin/operativo), panel de administración y almacenamiento en base de datos MySQL.

---

## Tecnologías utilizadas

| Capa | Tecnología |
|---|---|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP 8 |
| Base de datos | MySQL (InfinityFree — `sql311.infinityfree.com`) |
| SGBD visual | phpMyAdmin |
| Hosting | InfinityFree |

---

## Código Fuente Frontend

| Archivo | Descripción |
|---|---|
| `frontend/Login.html` | Página de inicio de sesión |
| `frontend/Login.css` | Estilos del login |
| `frontend/Login.js` | Lógica del login (fetch, validaciones, ojo de contraseña) |
| `frontend/registro.html` | Formulario de registro de nuevos usuarios |
| `frontend/registrer.css` | Estilos del registro |
| `frontend/index.php` | Formulario principal de recepción de productos (protegido por sesión) |
| `frontend/admin.php` | Panel de administración (solo rol admin) |
| `frontend/estilos.css` | Estilos globales del sistema |

---

## Código Fuente Backend

| Archivo | Descripción |
|---|---|
| `backend/login.php` | Autenticación de usuarios, manejo de sesión y redirección por rol |
| `backend/logout.php` | Cierre de sesión y destrucción de sesión PHP |
| `backend/registro.php` | Registro de nuevos usuarios con hash bcrypt y validaciones |
| `backend/guardar_excel.php` | Guardado de registros de recepción en base de datos MySQL |
| `backend/admin_data.php` | API de datos para el panel admin (estadísticas, recepciones, usuarios) |
| `backend/get_user.php` | Retorna el nombre del usuario activo desde la sesión |

---

## Estructura de la base de datos

### Tabla `usuarios`
| Campo | Tipo | Descripción |
|---|---|---|
| id | INT PK AUTO_INCREMENT | Identificador único |
| username | VARCHAR(100) | Nombre de usuario |
| password | VARCHAR(255) | Contraseña hasheada con bcrypt |
| nombre_completo | VARCHAR(150) | Nombre completo del usuario |
| correo_electronico | VARCHAR(150) | Correo electrónico |
| permisos | VARCHAR(20) | Rol: `admin` u `operativo` |

### Tabla `recepciones`
| Campo | Tipo | Descripción |
|---|---|---|
| id | INT PK AUTO_INCREMENT | Identificador único |
| usuario | VARCHAR(100) | Usuario que registró |
| fecha | DATE | Fecha de recepción |
| proveedor | VARCHAR(100) | Proveedor del producto |
| producto | VARCHAR(200) | Nombre del producto |
| cantidad | INT | Cantidad recibida |
| unidad | VARCHAR(20) | Unidad (Caja/Kg/Pieza) |
| precio_unidad | DECIMAL(10,2) | Precio por unidad |
| sensorial_olor | VARCHAR(10) | Evaluación olor (Si/No) |
| sensorial_color | VARCHAR(10) | Evaluación color (Si/No) |
| sensorial_textura | VARCHAR(10) | Evaluación textura (Si/No) |
| temp_producto | DECIMAL(5,1) | Temperatura del producto °C |
| empaque_limpio | VARCHAR(5) | Estado del empaque (Si/No) |
| num_remision | VARCHAR(100) | Número de remisión |
| verifico | VARCHAR(100) | Persona que verificó |
| fecha_registro | TIMESTAMP | Fecha/hora de registro automático |

---

## Características del sistema

- ✅ Autenticación segura con `password_hash()` (bcrypt)
- ✅ Protección de sesiones PHP en todas las páginas
- ✅ Prevención de SQL Injection con Prepared Statements
- ✅ Roles de usuario: `admin` y `operativo`
- ✅ Panel de administración con estadísticas en tiempo real
- ✅ Exportación de registros a CSV
- ✅ Formulario de recepción con validaciones
- ✅ Evaluación sensorial de productos (olor, color, textura)
- ✅ Control de temperatura con slider interactivo
- ✅ Registro de múltiples productos por recepción

---

## Pasos para ejecutar y comprobar la entrega

### Opción 1 — Sistema en línea (recomendado)

1. Abrir: https://carnicerialaidealescom1.page.gd/CARNES/Login.html
2. Iniciar sesión con las credenciales de prueba:

| Usuario | Contraseña | Rol |
|---|---|---|
| `Ricardo` | `Ricardo123!` | Operativo |

3. Llenar el formulario de recepción y dar clic en **Guardar**
4. Para acceder al panel admin, iniciar sesión con una cuenta de rol `admin`

### Opción 2 — Ejecución local

**Requisitos:** XAMPP (Apache + MySQL), PHP 8.0+

```bash
# 1. Copiar todos los archivos en:
C:/xampp/htdocs/CARNES/

# 2. Crear la base de datos en phpMyAdmin ejecutando:
CREATE DATABASE carniceria_db;

# 3. Crear las tablas (ver sección "Estructura de la base de datos")

# 4. Cambiar datos de conexión en login.php, registro.php y guardar_excel.php:
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'carniceria_db';

# 5. Abrir en navegador:
http://localhost/CARNES/Login.html
```

---

## Notas especiales

- La base de datos está en **InfinityFree** (`sql311.infinityfree.com`) — para ejecución local cambiar los datos de conexión como se indica arriba.
- Las contraseñas se almacenan con `password_hash()` algoritmo **bcrypt**.
- Todas las consultas usan **Prepared Statements** para prevenir SQL Injection.
- Solo usuarios con rol `admin` pueden acceder al panel de administración.
