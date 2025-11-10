
# 🏫 Sistema Académico - Documentación Completa MVC

## 📚 MODELOS (Capa de Datos)

### 🗃️ **CarreraModel** (`CarreraModel.php`)
**Tabla:** `carreras`

#### 🎯 Características:
- **Eliminación lógica** mediante campo `estado` (1=activo, 0=inactivo)
- **Validación robusta** con reglas específicas
- **Callback automático** para establecer estado por defecto

#### ⚙️ Configuración:
```php
protected $allowedFields = ['id_categoria', 'nombre_carrera', 'duracion', 'modalidad', 'estado'];
🔧 Métodos Especiales:
findAllActive() - Solo carreras activas

softDelete($id) - Eliminación lógica

🗃️ CategoriaModel (CategoriaModel.php)
Tabla: categorias

🎯 Características:
Timestamps personalizados (fecha_creacion, fecha_actualizacion)

Exportación automática a JSON después de cada inserción

Gestión de archivos en directorio writable/exports/

🔄 Callback de Exportación:
php
protected $afterInsert = ['guardarComoJSON'];
🗃️ CursoModel (CursoModel.php)
Tabla: cursos

🎯 Características:
Soft Deletes completo con deleted_at

Timestamps automáticos para creación y actualización

Estructura educativa con créditos y cupos máximos

⚙️ Configuración:
php
protected $useSoftDeletes = true;
protected $useTimestamps = true;
🗃️ EstudianteModel (EstudianteModel.php)
Tabla: alumnos

🎯 Características:
Validación de unicidad para DNI y email

Exportación automática a JSON

Integración con usuarios mediante id_usuario

⚙️ Configuración:
php
protected $validationRules = [
    'dni_matricula' => 'required|max_length[15]|is_unique[alumnos.dni_matricula]',
    'email' => 'required|valid_email|is_unique[alumnos.email]'
];
🗃️ InscripcionModel (InscripcionModel.php)
Tabla: inscripciones

🎯 Características:
Gestión de relaciones entre alumnos y cursos

Mensajes de error personalizados

Timestamps estándar para seguimiento

🗃️ ProfesorModel (ProfesorModel.php)
Tabla: profesores

🎯 Características:
Sin timestamps (tabla no tiene campos de fecha)

Exportación automática a JSON

Especialización académica integrada

🗃️ UsuarioModel (UsuarioModel.php)
Tabla: usuarios

🎯 Características:
Validación de email como nombre de usuario

Sistema de roles (admin, profesor, alumno)

Gestión de estados (activo/inactivo)

🎮 CONTROLADORES (Capa de Lógica de Negocio)
🎛️ BaseController (BaseController.php)
Controlador base del que heredan todos los demás

🎯 Características:
Clase abstracta que extiende Controller de CodeIgniter

Helper de URL cargado automáticamente

Inicialización centralizada de recursos comunes

🎛️ Home (Home.php)
Controlador de la página principal

🔧 Método Principal:
php
public function index(): string
{
    return view('home');
}
🎛️ Carreras (Carreras.php)
Gestión completa del CRUD de carreras

🔧 Métodos Implementados:
index() - Lista carreras activas

crear() - Formulario de creación

guardar() - Procesamiento con validación

editar($id) - Formulario de edición

actualizar() - Procesamiento de actualización

eliminar($id) - Eliminación lógica

🎛️ Categorias (Categorias.php)
Gestión de categorías de carreras

🎯 Características:
Formulario dual (crear/editar en misma vista)

Exportación automática a JSON

Validación de unicidad inteligente

🎛️ Cursos (Cursos.php)
Gestión completa de cursos académicos

🎯 Características:
Soft Deletes implementados

Relaciones complejas con profesores y carreras

Métodos helper para carga de datos relacionados

🔗 Relaciones:
php
protected function findAllWithRelations()
{
    return $this->cursoModel
        ->select('cursos.*, p.nombre_completo as nombre_profesor, c.nombre_carrera')
        ->join('profesores p', 'p.id_profesor = cursos.id_profesor', 'left')
        ->join('carreras c', 'c.id_carrera = cursos.id_carrera', 'left')
        ->findAll();
}
🎛️ Estudiantes (Estudiantes.php)
Gestión de estudiantes con inscripciones

🎯 Características:
Sistema de inscripciones integrado

Validación de unicidad para DNI y email

Manejo de relaciones con carreras y cursos

🎛️ Profesores (Profesores.php)
Gestión de profesores con sistema de usuarios integrado

🎯 Características:
Creación dual (profesor + usuario)

Transacciones de base de datos para consistencia

Generación automática de contraseñas temporales

🎛️ Inscripcion (Inscripcion.php)
Gestión de inscripciones de estudiantes a cursos

🔧 Métodos Implementados:
inscribir() - Crea nueva inscripción

desinscribir($id_alumno) - Soft Delete de última inscripción

🎛️ Login (Login.php)
Sistema de autenticación y gestión de sesiones

🔐 Flujo de Autenticación:
php
// 1. Buscar usuario por email
// 2. Verificar contraseña  
// 3. Crear sesión
🎨 VISTAS (Capa de Presentación)
🎯 Layout Principal (templates/layout)
Base común para todas las páginas

🎯 Características:
Estructura HTML completa

Sistema de secciones para contenido dinámico

Inclusión de recursos CSS y JavaScript

⚙️ Estructura Básica:
php
<?= $this->extend('templates/layout') ?>
<?= $this->section('content') ?>
<!-- Contenido específico -->
<?= $this->endSection() ?>
🎯 Login (login.php)
Formulario de autenticación del sistema

🔐 Campos del Formulario:
php
<input type="email" name="nombre_de_usuario" required>
<input type="password" name="contrasena" required>
🎨 Estilos Específicos:
Contenedor centrado verticalmente

Card con sombras y bordes redondeados

Alertas personalizadas

🎯 Home (home.php)
Página principal del sistema académico

🎪 Secciones Principales:
Hero Section - Presentación principal

About Us - Video y descripción

Gallery - Carrusel de imágenes

Steps - Guía de uso

Instituto Info - Historia y datos

🔧 Componentes Interactivos:
Glider.js para carrusel

Video HTML5 con controles

Botones de navegación

🎯 Gestión de Carreras
carreras_list.php - Lista de carreras
🎯 Características:

Tabla responsive con datos

Badges de estado (Activa/Inactiva)

Botones de acción personalizados

carreras_form.php - Formulario de carreras
🎯 Características:

Formulario dual (crear/editar)

Validación visual de campos

Dropdowns dinámicos

🎯 Gestión de Categorías
categorias_list.php - Lista de categorías
🎯 Características:

Tabla simple con CRUD completo

Mensajes de empty state

Acciones inline

categorias_form.php - Formulario de categorías
🎯 Características:

Formulario minimalista

Validación en tiempo real

Modo dual creación/edición

🎯 Gestión de Cursos
cursos.php - Lista de cursos
🎯 Características:

Tabla con relaciones (profesor y carrera)

Datos enriquecidos desde JOINs

Eliminación lógica con confirmación

cursos_form.php - Formulario de cursos
🎯 Características:

Grid de 3 columnas para organización

Dropdowns relacionados

Validación completa

🎯 Gestión de Estudiantes
estudiantes.php - Lista y gestión integral
🎯 Características:

Dos secciones principales: CRUD + Inscripciones

Tabla de estudiantes con acciones

Gestión de inscripciones integrada

🔄 Sección de Inscripciones:
Inscripción rápida con dropdown

Visualización de cursos actuales

Desinscripción con confirmación

estudiantes_form.php - Formulario de estudiantes
🎯 Características:

Campo DNI corregido (dni_matricula)

Validación visual mejorada

Dropdown de carreras dinámico

🎯 Gestión de Profesores
profesores.php - Lista de profesores
🎯 Características:

Tabla con datos completos

Eliminación con confirmación

Encabezado centrado

profesores_form.php - Formulario de profesores
🎯 Características:

Formulario simplificado

Validación de email único

Campos esenciales

🔄 RELACIÓN ENTRE CAPAS
📊 Ejemplo de Flujo Completo:
Modelo (CarreraModel):
php
// Define estructura de datos y reglas
public function softDelete($id) { 
    return $this->update($id, ['estado' => 0]); 
}
Controlador (Carreras):
php
public function eliminar($id)
{
    $carreraModel = new CarreraModel();
    $carreraModel->softDelete($id);
    return redirect()->to(base_url('carreras'))->with('mensaje', 'Carrera eliminada');
}
Vista (carreras_list.php):
php
<!-- Muestra el resultado -->
<?= view('templates/_alerts') ?>
<!-- Muestra lista actualizada -->
🛠️ PATRONES COMUNES IMPLEMENTADOS
✅ Validaciones
Modelos: Definen reglas de validación

Controladores: Aplican validaciones

Vistas: Muestran errores

🔄 Transacciones de Base de Datos
php
$db->transStart();
try {
    // Operaciones múltiples
    $db->transComplete();
} catch (\Exception $e) {
    $db->transRollback();
}
📊 Manejo de Sesiones
Controladores: Gestionan autenticación

Vistas: Muestran mensajes

Modelos: No manejan sesiones

🎨 Componentes Reutilizables
Sistema de grids para formularios

Alertas unificadas desde template

Botones de acción estandarizados

Tablas responsive con clases comunes

🎯 DIFERENCIAS CLAVE ENTRE CAPAS
🗃️ MODELOS:
✅ Gestionan estructura de datos

✅ Definen validaciones y reglas

✅ Operaciones CRUD básicas

✅ Callbacks automáticos

❌ No manejan HTTP requests

❌ No renderizan vistas

🎮 CONTROLADORES:
✅ Gestionan flujo de aplicación

✅ Procesan formularios y requests

✅ Coordinan múltiples modelos

✅ Renderizan vistas

✅ Manejan redirecciones y sesiones

❌ No definen estructura de datos

🎨 VISTAS:
✅ Presentan datos al usuario

✅ Contienen HTML y estilos

✅ Reciben datos de controladores

✅ Muestran formularios y mensajes

❌ No contienen lógica de negocio

❌ No acceden directamente a modelos

🔄 FLUJOS DE TRABAJO PRINCIPALES
1. Autenticación
text
login.php (Vista) → Login/auth() (Controlador) → UsuarioModel (Modelo) → Sesión → home.php (Vista)
2. CRUD Básico
text
lista (Vista) → crear() (Controlador) → formulario (Vista) → guardar() (Controlador) → Modelo → redirección
3. Gestión de Inscripciones
text
estudiantes.php (Vista) → inscribir() (Controlador) → InscripcionModel (Modelo) → actualización en vista
📋 RESUMEN DE ENTIDADES
Entidad	Modelo	Controlador	Vistas Principales
Carreras	CarreraModel	Carreras	carreras_list, carreras_form
Categorías	CategoriaModel	Categorias	categorias_list, categorias_form
Cursos	CursoModel	Cursos	cursos, cursos_form
Estudiantes	EstudianteModel	Estudiantes	estudiantes, estudiantes_form
Profesores	ProfesorModel	Profesores	profesores, profesores_form
Inscripciones	InscripcionModel	Inscripcion	(integrado en estudiantes)
Usuarios	UsuarioModel	Login	login
🚀 ESTRUCTURA DEL PROYECTO
text
app/
├── Models/
│   ├── CarreraModel.php
│   ├── CategoriaModel.php
│   ├── CursoModel.php
│   ├── EstudianteModel.php
│   ├── InscripcionModel.php
│   ├── ProfesorModel.php
│   └── UsuarioModel.php
├── Controllers/
│   ├── BaseController.php
│   ├── Home.php
│   ├── Carreras.php
│   ├── Categorias.php
│   ├── Cursos.php
│   ├── Estudiantes.php
│   ├── Profesores.php
│   ├── Inscripcion.php
│   └── Login.php
└── Views/
    ├── templates/
    │   ├── layout.php
    │   └── _alerts.php
    ├── home.php
    ├── login.php
    ├── carreras_list.php
    ├── carreras_form.php
    ├── categorias_list.php
    ├── categorias_form.php
    ├── cursos.php
    ├── cursos_form.php
    ├── estudiantes.php
    ├── estudiantes_form.php
    ├── profesores.php
    └── profesores_form.php


## 🔒 FILTROS Y RUTAS (Capas de Seguridad y Acceso)

Esta sección detalla cómo se protegen las rutas y se gestiona el acceso mediante el sistema de **Filtros** de CodeIgniter 4.

### 🎚️ **Definición de Filtros (`Config/Filters.php`)**

Se definen dos alias clave para proteger las rutas, importando las clases personalizadas:

| Alias | Clase | Propósito |
| :--- | :--- | :--- |
| `auth` | **AuthFilter** | Verificación de **sesión iniciada** simple (`isLoggedIn`). |
| `role` | **RoleFilter** | Verificación de `isLoggedIn`, **cambio de contraseña obligatorio**, y **roles permitidos** (recibe argumentos). |

### 🚦 **Lógica de Filtros (`App/Filters/`)**

Los filtros ejecutan su lógica **ANTES** (`before`) de que se ejecute el controlador:

#### 1. AuthFilter (`AuthFilter.php`)
* **Verificación Simple:** Comprueba si `session()->get('isLoggedIn')` es `false`.
* **Acción:** Si no ha iniciado sesión, redirige a `base_url('login')` con un mensaje de error *flashdata*.

#### 2. RoleFilter (`RoleFilter.php`)
Este filtro aplica tres niveles de seguridad secuenciales:

1.  **Verificación de Autenticación:** Si el usuario no está `isLoggedIn`, redirige a `/login`.
2.  **Verificación de Cambio Obligatorio:** Si `session()->get('cambio_obligatorio') == 1` Y la URI actual (`$request->getUri()->getPath()`) **NO** es la página de cambio de contraseña (`perfil/cambio-contrasena`), **fuerza la redirección** a esa página con un mensaje de advertencia.
3.  **Verificación de Roles:** Si se pasan argumentos de roles (ej: `administrador`) y el rol del usuario (`$user_role`) **NO** está en la lista permitida, se deniega el acceso y se redirige al *dashboard* específico de su rol (ej: `admin/dashboard`, `profesores`, etc.).

### 🗺️ **Definición de Rutas (`Config/Routes.php`)**

Las rutas utilizan los alias definidos en `Filters.php` para proteger el acceso:

* **Rutas de Autenticación:** `login` y `logout` son públicas.
* **Rutas Protegidas por Rol:** El CRUD completo para `profesores`, `carreras`, `categorias`, `cursos`, `estudiantes` e `inscripciones` está agrupado y protegido usando el filtro `role` con el argumento `administrador`.
    ```php
    $routes->group('profesores', ['filter' => 'role:administrador'], static function ($routes) {
        // ... Rutas de CRUD
    });
    ```
* **Rutas de Perfil/Seguridad:** Las rutas de cambio de contraseña (`perfil/cambio-contrasena`, `perfil/actualizar-contrasena`) se protegen con el filtro `auth` (verificación simple de inicio de sesión).

---

## 🔄 FLUJO DE TRABAJO PRINCIPAL

| Flujo | Capas Involucradas | Resumen de Pasos |
| :--- | :--- | :--- |
| **Autenticación** | Vista → Controlador → Modelo → Filtro | `login.php` → `Login::auth()` → `UsuarioModel` → Crea Sesión → Rutas Protegidas por `RoleFilter`. |
| **CRUD (Admin)** | Ruta/Filtro → Controlador → Modelo → Vista | Solicitud a `/profesores/eliminar/1` → `RoleFilter` aprueba (si es admin) → `Profesores::eliminar()` → Usa **Transacción** con `ProfesorModel` y `UsuarioModel` → Redirige a lista. |
| **Eliminación Lógica** | Controlador → Modelo | `Carreras::eliminar()` llama al método del `CarreraModel` para actualizar el campo `estado` a `0`. |