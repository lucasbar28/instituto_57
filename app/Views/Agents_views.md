🎨 VISTAS - Sistema Académico 
📋 VISTAS PRINCIPALES
Login (login.php)

Autenticación centrada con CSRF

Campos: nombre_de_usuario (email) y contrasena

Home (home.php, index.php)

Página institucional con carrusel, video y navegación

📊 LISTADOS CRUD
Vista	Columnas Principales	Acciones
carreras_list.php	Nombre, Duración, Modalidad, Estado	Editar, Desactivar
categorias_list.php	Nombre, Descripción, Fecha	Editar, Eliminar
cursos.php	Código, Nombre, Créditos, Profesor, Carrera	Editar, Eliminar
estudiantes.php	DNI, Nombre, Email, Carrera	Editar, Eliminar + Inscripciones
profesores.php	Nombre, Especialidad, Email, Teléfono	Editar, Eliminar
📝 FORMULARIOS
Estructura uniforme:

php
<?= $this->extend('templates/layout') ?>
<?= $this->section('content') ?>
<!-- Form dual (crear/editar) -->
<?= $this->endSection() ?>
Formularios disponibles:

carreras_form.php - Con dropdowns: Modalidad, Categoría

categorias_form.php - Minimalista (nombre + descripción)

cursos_form.php - Grid 3 cols + relaciones

estudiantes_form.php - Con DNI y carrera

profesores_form.php - Grid 2 cols sin contraseña

🎨 PATRONES COMUNES
Componentes:

php
<?= view('templates/_alerts') ?>
<a class="btn-action btn-edit"><i class="fas fa-edit"></i> Editar</a>
<a class="btn-action btn-delete"><i class="fas fa-trash"></i> Eliminar</a>
Grids CSS:

form-grid (básico)

form-grid-2-col

form-grid-3

form-group-full

🔄 FLUJOS
text
Login → Home → Módulos
Lista → Crear/Editar → Guardar → Lista actualizada
Estudiantes → Inscripción rápida → Desinscripción
✅ ESTADO
Completado: 5 formularios + 7 listas + 2 principales
Característica especial: Gestión integrada de inscripciones en estudiantes