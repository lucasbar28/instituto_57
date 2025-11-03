# 📚 MODELOS - Sistema Académico

## 🗃️ MODELOS DISPONIBLES (CodeIgniter 4)

| Modelo | Tabla | Clave Primaria | Característica Principal |
| :--- | :--- | :--- | :--- |
| **UsuarioModel** | `usuarios` | `id` | Gestión de credenciales, roles y autenticación. |
| **ProfesorModel** | `profesores` | `id_profesor` | Exportación automática a JSON después de la inserción. |
| **EstudianteModel** | `alumnos` | `id_alumno` | Validaciones de unicidad en DNI/Matrícula y Email. |
| **CategoriaModel** | `categorias` | `id_categoria` | Uso de TimeStamps personalizados. Exportación a JSON. |
| **CarreraModel** | `carreras` | `id_carrera` | Eliminación lógica mediante el campo `estado`. |
| **CursoModel** | `cursos` | `id_curso` | Uso de **Soft Deletes** completo (`deleted_at`). |
| **InscripcionModel** | `inscripciones` | `id_inscripcion` | Registra el estado (`estado`) y fecha de inscripción. |

---

## 🔧 CONFIGURACIONES PRINCIPALES

### ⏰ TimeStamps (Fechas de Creación/Actualización)

| Modelo | `useTimestamps` | Campos Usados |
| :--- | :--- | :--- |
| **CategoriaModel** | `true` | `fecha_creacion`, `fecha_actualizacion` (Personalizados) |
| **CursoModel** | `true` | `created_at`, `updated_at` (Estándar) |
| **InscripcionModel** | `true` | `created_at`, `updated_at` (Estándar) |
| **CarreraModel** | `false` | *No usados* |
| **EstudianteModel** | `false` | *No usados* |
| **ProfesorModel** | `false` | *No usados* |
| **UsuarioModel** | `false` | *No usados* |

### 🗑️ Gestión de Borrados (Delete Handling)

| Modelo | Mecanismo | Campo / Característica |
| :--- | :--- | :--- |
| **CursoModel** | **Soft Delete** | `deleted_at` (El registro se marca como borrado, pero no se elimina de la DB) |
| **CarreraModel** | **Eliminación Lógica** | Campo `estado` (`1`=activo, `0`=inactivo). Posee método `findAllActive()` |
| **InscripcionModel** | Borrado Físico | Se gestiona el estado ('Activo'/'Inactivo') en el controlador. |
| **UsuarioModel** | Borrado Físico | Estándar. |
| **ProfesorModel** | Borrado Físico | Estándar. |
| **EstudianteModel** | Borrado Físico | Estándar. |
| **CategoriaModel** | Borrado Físico | Estándar. |

---

## 📋 VALIDACIONES DESTACADAS

### 🔒 Unicidad (`is_unique`) y Restricciones

| Modelo | Campo Validado | Regla de Unicidad / Restricción |
| :--- | :--- | :--- |
| **EstudianteModel** | `dni_matricula` | `required|is_unique[alumnos.dni_matricula,id_alumno,{id_alumno}]` |
| **EstudianteModel** | `email` | `required|valid_email|is_unique[alumnos.email,id_alumno,{id_alumno}]` |
| **UsuarioModel** | `nombre_de_usuario` | `required|valid_email|is_unique[usuarios.nombre_de_usuario]` |
| **CarreraModel** | `modalidad` | `in_list[Presencial,Virtual,Mixta]` |
| **UsuarioModel** | `rol` | `required|in_list[admin,profesor,alumno]` |
| **UsuarioModel** | `estado` | `required|in_list[activo,inactivo]` |

---

## ⚙️ CARACTERÍSTICAS AVANZADAS Y CALLBACKS

### 🔄 Callbacks de Eventos

| Modelo | Evento | Callback / Función | Lógica |
| :--- | :--- | :--- | :--- |
| **CarreraModel** | `beforeInsert` | `setDefaultEstado` | Asigna `estado = 1` (Activo) si no se proporciona al insertar. |
| **ProfesorModel** | `afterInsert` | `guardarComoJSON` | Exporta el registro completo a un archivo JSON en `writable/exports/`. |
| **EstudianteModel** | `afterInsert` | `guardarComoJSON` | Exporta el registro completo a un archivo JSON en `writable/exports/`. |
| **CategoriaModel** | `afterInsert` | `guardarComoJSON` | Exporta el registro completo a un archivo JSON en `writable/exports/`. |

### 🎯 Métodos Personalizados

| Modelo | Método | Propósito |
| :--- | :--- | :--- |
| **CarreraModel** | `findAllActive()` | Recupera solo las carreras cuyo campo `estado` es `1` (Activo). |
| **CarreraModel** | `logicalDelete($id)` | Realiza la eliminación lógica actualizando `estado` a `0`. |

---

## 🔗 RELACIONES IMPLÍCITAS (Claves Foráneas)

| Relación | Modelos Involucrados | Campo de Unión (FK) |
| :--- | :--- | :--- |
| **Autenticación** | Usuario → Profesor/Estudiante | `id_usuario` |
| **Académica** | Categoría → Carrera | `id_categoria` |
| **Académica** | Carrera → Curso | `id_carrera` |
| **Recursos Humanos** | Profesor → Curso | `id_profesor` |
| **Registro** | Estudiante → Inscripción | `id_alumno` |
| **Registro** | Curso → Inscripción | `id_curso` |