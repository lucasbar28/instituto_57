🎨 VISTAS - Sistema Académico
📋 VISTAS PRINCIPALES
🔐 Login (login.php)
Formulario de autenticación centrado

Campos: email (nombre_de_usuario) y contraseña

Protección CSRF integrada

Alertas para mensajes de error/éxito

🏠 Home (home.php)
Página principal con diseño institucional

5 secciones: Hero, About Us, Gallery, Steps, Instituto Info

Componentes: Carrusel de imágenes, video, botones de navegación

Enlaces a módulos principales

📊 VISTAS DE LISTADO
🎓 carreras_list.php
Tabla con carreras activas

Badges de estado (Activa/Inactiva)

Acciones: Editar, Eliminar (lógica)

🏷️ categorias_list.php
Lista simple de categorías

Columnas: ID, Nombre, Descripción, Fecha, Acciones

Empty state personalizado

📚 cursos.php
Tabla con relaciones JOIN (profesor, carrera)

Datos enriquecidos desde controlador

Eliminación lógica con confirmación

👨‍🎓 estudiantes.php
Dos secciones: CRUD + Gestión de Inscripciones

Inscripciones rápidas con dropdown

Visualización de cursos inscritos

Desinscripción con confirmación

👨‍🏫 profesores.php
Lista completa de profesores

Eliminación con confirmación avanzada

Formulario DELETE para eliminación

📝 VISTAS DE FORMULARIOS
🎓 carreras_form.php
Formulario dual (crear/editar)

Dropdowns: Modalidad, Categoría

Estado solo visible en edición

Validación visual

🏷️ categorias_form.php
Formulario minimalista (nombre + descripción)

Modo creación/edición automático

Validación en tiempo real

📚 cursos_form.php
Grid de 3 columnas para organización

Dropdowns relacionados: Profesores, Carreras

Campos: Nombre, Código, Créditos, Cupo, Descripción

Validación completa

👨‍🎓 estudiantes_form.php
Campo DNI corregido (dni_matricula)

Dropdown de carreras dinámico

Validación de unicidad (DNI, email)

👨‍🏫 profesores_form.php
Formulario simplificado (sin contraseña)

Grid 2 columnas

Campos esenciales para perfil docente

🎨 PATRONES DE DISEÑO
🔧 Componentes Reutilizables
php
<!-- Estructura base -->
<?= $this->extend('templates/layout') ?>
<?= $this->section('content') ?>
<!-- Contenido -->
<?= $this->endSection() ?>

<!-- Alertas -->
<?= view('templates/_alerts') ?>

<!-- Botones de acción -->
<a href="#" class="btn-action btn-edit"><i class="fas fa-edit"></i> Editar</a>
<a href="#" class="btn-action btn-delete"><i class="fas fa-trash"></i> Eliminar</a>
📱 Grids Responsive
form-grid - Básico

form-grid-2-col - 2 columnas

form-grid-3 - 3 columnas

form-group-full - Ancho completo

✅ Validación Visual
php
<input class="form-control <?= $has_error('campo') ?>" >
<small class="invalid-feedback-text"><?= $validation->getError('campo') ?></small>
🚀 FLUJOS PRINCIPALES
Login → Home

Home → Módulos (Estudiantes, Carreras, Cursos, etc.)

Lista → Crear → Formulario → Guardar → Lista actualizada

Lista → Editar → Formulario → Actualizar → Lista actualizada

Estudiantes → Inscripción rápida → Desinscripción

📊 RESUMEN
Tipo	Vistas	Característica Principal
Auth	login	Autenticación
Principal	home	Página institucional
Listas	5 vistas	Tablas con CRUD
Formularios	5 vistas	Crear/Editar dual
Especial	estudiantes	CRUD + Inscripciones integradas