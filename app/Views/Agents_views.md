# 🖥️ VISTAS (VIEWS) - Interfaz de Usuario

Este documento resume la funcionalidad y la estructura de las plantillas (vistas) utilizadas en el sistema, construidas sobre un layout base (`templates/layout`).

---

## 🚪 1. VISTAS DE ACCESO Y PÁGINAS PRINCIPALES

| Archivo | Propósito Principal | Ruta Implícita | Componentes Destacados |
| :--- | :--- | :--- | :--- |
| **index.php** | **Landing Page / Aplicación** | `/` | 🌐 **Hero-Section:** Bienvenida a "StudentApp". 🖼️ **Galería:** Uso del componente `glider-container` (slider). 🛠️ **Pasos:** Guía de uso de la aplicación (Estudiantes, Carreras, Categorías). |
| **home.php** | **Página de Inicio Institucional** | `/home` | 📹 **Video:** Muestra un video local (`videos/practicas.mp4`). 📰 **Info Institucional:** Contenido sobre el I.S.F.D. y T. N°57. Estructura similar a `index.php` con un enfoque educativo. |
| **login.php** | **Inicio de Sesión** | `/login` | 🔒 Formulario `POST` a `login/auth`. Maneja y muestra mensajes de error (`session()->getFlashdata('msg')`). Utiliza los campos `nombre_de_usuario` (Email) y `contrasena`. |
| **Common.php** | **Configuración Central** | (Helper/Core) | Archivo CodeIgniter 4 vacío, destinado a la sobreescritura de funciones de *core* o adición de *helpers* globales. |

---

## 📊 2. VISTAS DE LISTADO (READ - CRUD)

Todas las listas usan la clase **`data-table`** y replican la estructura de alertas y títulos centrados (`d-flex flex-column...`) para una experiencia de usuario consistente.

### 👥 Profesores (profesores.php)

| Columna | Acciones de Fila | Notas |
| :--- | :--- | :--- |
| Nombre, Email, Especialidad, Teléfono. | **Editar** (`profesores/editar/{id}`) | **Eliminación Fuerte/Permanente** mediante un formulario `DELETE` con advertencia de confirmación (`⚠️ ADVERTENCIA: Esta acción es PERMANENTE.`). |

### 🎓 Estudiantes (estudiantes.php)

| Columna | Acciones de Fila | Notas |
| :--- | :--- | :--- |
| DNI/Matrícula, Email, Carrera, **Curso Actual**. | **Editar** (`estudiantes/editar/{id}`) | ✅ **Inscripción Rápida:** Formulario de acción directa con un *dropdown* para seleccionar el curso a inscribir. ❌ **Desinscripción:** Botón de desinscripción que apunta al controlador `inscripciones/desinscribir/{id_alumno}`. |

### 📚 Cursos (cursos.php)

| Columna | Acciones de Fila | Notas |
| :--- | :--- | :--- |
| Nombre, Código, Créditos, Cupo, **Profesor Asignado**, **Carrera**. | **Editar** (`cursos/editar/{id}`) | **Eliminación Lógica (Soft Delete)**: Se presenta como un botón "Eliminar" con una confirmación que indica que se eliminará *lógicamente*. |

---

## ✍️ 3. VISTAS DE FORMULARIO (CREATE/UPDATE - CRUD)

Todos los formularios utilizan la lógica PHP para detectar el **modo Edición (`$is_edit`)** y cambian dinámicamente:
1.  La URL de acción (`/guardar` o `/actualizar`).
2.  El título del formulario.
3.  El texto y el ícono del botón principal.

### 📝 Formularios de Gestión de Personas

| Archivo | Entidad | Campos Principales | Lógica de Datos |
| :--- | :--- | :--- | :--- |
| **profesores_form.php** | Profesor | Nombre, Email, Especialidad, Teléfono. | Utiliza `old()` para prellenar datos en caso de error de validación. El campo de contraseña inicial ha sido **eliminado**. |
| **estudiantes_form.php** | Estudiante | DNI/Matrícula, Nombre, Email, Teléfono, **Carrera** (`id_carrera`). | Usa un *dropdown* (`<select>`) para seleccionar la `id_carrera`. Maneja `old()` para la persistencia de datos. |

### 📝 Formulario de Gestión Académica

| Archivo | Entidad | Campos Principales | Lógica de Datos |
| :--- | :--- | :--- | :--- |
| **cursos_form.php** | Curso | Nombre, Código, Créditos, Cupo Máximo, Descripción. **Profesor** (`id_profesor`), **Carrera** (`id_carrera`). | Usa dos *dropdowns* (`<select>`) para establecer la relación con Profesor y Carrera. El campo `descripcion` es opcional (`Opcional`). |

---

## 🎨 4. ELEMENTOS DE LAYOUT Y DISEÑO

| Componente | Uso Común | Notas de Implementación |
| :--- | :--- | :--- |
| **Layout** | `<?= $this->extend('templates/layout') ?>` | Todas las vistas principales (listados y formularios) heredan de una plantilla base. |
| **Alertas** | `<?= view('templates/_alerts') ?>` | Se usa una vista parcial o lógica para manejar y mostrar mensajes de sesión (`flashdata`) de éxito (`mensaje`) o error (`error`). |
| **Validación** | `<?= $validation->getError('campo') ?>` | Se utiliza el servicio `Config\Services::validation()` en los formularios para mostrar mensajes de error debajo del campo correspondiente, con clases de estilo personalizadas como `invalid-feedback-text`. |
| **Clases CSS** | `data-table`, `btn-action`, `btn-edit`, `btn-delete` | Uso de clases CSS personalizadas para tablas y botones de acción CRUD, asegurando un diseño uniforme en toda la aplicación. |