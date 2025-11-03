# 🎮 CONTROLADORES - Sistema Académico
## 🏗️ ESTRUCTURA BASE

### 🎛️ BaseController (BaseController.php)
Clase base de la que heredan todos los demás controladores.
* Incluye el helper `'url'` para la generación de rutas.

---

## 🔐 CONTROLADORES DE AUTENTICACIÓN Y ACCESO

### Login (Login.php)
Sistema de autenticación de usuarios.

| Método | Descripción | Característica Especial |
| :--- | :--- | :--- |
| `index()` | Muestra el formulario de inicio de sesión. | |
| `auth()` | Procesa la autenticación. | 1. Busca el usuario por `nombre_de_usuario`. 2. Verifica la `contrasena` usando `password_verify()`. 3. Crea la sesión con `id_usuario`, `username`, `rol` y `isLoggedIn`. |
| `logout()` | Cierra la sesión del usuario. | |

---

## 👥 CONTROLADORES DE GESTIÓN DE PERSONAS

### Profesores (Profesores.php)
Gestión de datos personales y credenciales de Profesores.

| Método | Descripción | Característica Especial |
| :--- | :--- | :--- |
| `index()` | Muestra la lista de todos los profesores. | |
| `crear()` | Muestra el formulario para registrar un nuevo profesor. | |
| `guardar()` | Procesa el formulario. | Inserta el registro de Usuario y luego el de Profesor. |
| `editar($id)` | Muestra el formulario con datos para editar. | |
| `actualizar($id)` | Procesa la actualización del formulario. | |
| `eliminar($id)` | **Elimina** el registro de Profesor. | Implementa una **Transacción de Base de Datos** para garantizar que se elimine tanto el registro de la tabla `profesores` como el registro de `usuarios` asociado. |

### Estudiantes (Estudiantes.php)
Gestión de datos personales de Estudiantes (Alumnos).

| Método | Descripción | Característica Especial |
| :--- | :--- | :--- |
| `index()` | Muestra la lista de estudiantes. | Obtiene y mapea las inscripciones activas para mostrar qué curso está tomando cada estudiante. |
| `crear()` | Muestra el formulario para registrar un nuevo estudiante. | Carga la lista de Carreras disponibles. |
| `guardar()` | Procesa el formulario e inserta el nuevo estudiante. | |
| `editar($id)` | Muestra el formulario con datos para editar. | |
| `actualizar($id)` | Procesa la actualización del formulario. | Actualiza los datos personales y la `id_carrera` asociada. |
| `eliminar($id)` | Elimina el registro del estudiante. | Maneja el error de llave foránea (Error 1451) si el estudiante tiene inscripciones asociadas. |

---

## 📚 CONTROLADORES DE GESTIÓN ACADÉMICA

### Carreras (Carreras.php)
Gestión de las Carreras Académicas.

| Método | Descripción | Característica Especial |
| :--- | :--- | :--- |
| `index()` | Muestra la lista de carreras. | Utiliza el método `findAllActive()` del modelo para filtrar solo las carreras con `estado = 1` (Activas). |
| `crear()` | Muestra el formulario de creación. | Carga la lista de Categorías. |
| `guardar()` | Procesa el formulario e inserta la nueva carrera. | |
| `editar($id)` | Muestra el formulario con datos para edición. | Carga la lista de Categorías. |
| `actualizar($id)` | Procesa la actualización. | |
| `eliminar($id)` | Ejecuta la eliminación. | Realiza una **Eliminación Lógica** (`update` al campo `estado` a 0) en lugar de la eliminación física. |

### Categorias (Categorias.php)
Gestión de Categorías para agrupar Carreras.

| Método | Descripción | Característica Especial |
| :--- | :--- | :--- |
| `index()` | Muestra la lista de todas las categorías. | |
| `crear($id)` | Muestra el formulario (se usa para Crear o Editar). | Pasa la categoría si se recibe un ID, sino pasa `null`. |
| `guardar()` | Procesa el formulario. | Realiza la lógica de **Inserción o Actualización** basándose en si se recibe un ID en los datos POST. |
| `eliminar($id)` | Elimina físicamente la categoría. | Utiliza el método `delete()` estándar. |

### Cursos (Cursos.php)
Gestión de los Cursos Académicos.

| Método | Descripción | Característica Especial |
| :--- | :--- | :--- |
| `index()` | Muestra la lista de cursos. | Usa el método interno `findAllWithRelations()` que realiza `JOIN`s para obtener el nombre de la Carrera y el Profesor. |
| `crear()` | Muestra el formulario de creación. | Carga las listas de Profesores y Carreras disponibles. |
| `guardar()` | Procesa el formulario e inserta el nuevo curso. | |
| `editar($id)` | Muestra el formulario con datos para edición. | Carga las listas de Profesores y Carreras disponibles. |
| `actualizar($id)` | Procesa la actualización. | |
| `eliminar($id)` | Ejecuta la eliminación. | Utiliza **Soft Delete** (marca el campo `deleted_at`) en el modelo del curso. |

### Inscripcion (Inscripcion.php)
Controlador para gestionar la inscripción de Estudiantes a Cursos.

| Método | Descripción | Característica Especial |
| :--- | :--- | :--- |
| `inscribir()` | Procesa la solicitud POST de inscripción. | 1. Recibe `id_alumno` e `id_curso`. 2. Asigna fecha de inscripción (`fecha_inscripcion`) y estado (`Activo`). 3. Inserta el registro. |
| `desinscribir()` | Procesa la solicitud POST para desinscribir. | 1. Busca la última inscripción activa del alumno para el curso. 2. Realiza un **Soft Delete** y actualiza el campo `estado` a 'Inactivo'. |

---

## 🔄 PATRONES COMUNES (BaseController / Código CodeIgniter)

### ✅ Validaciones
Uso del servicio `Config\Services::validation()` para verificar datos.
```php
if (!$this->validate([
    'campo' => 'required|min_length[3]|is_unique[tabla.campo]',
])) {
    return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
}
🔄 Redirecciones con Mensajes (Flashdata)
Se usan métodos with() para enviar mensajes temporales a la vista (Flashdata).

PHP

// Éxito
return redirect()->to('entidad')->with('mensaje', '✅ Operación exitosa');

// Error  
return redirect()->back()->withInput()->with('error', '❌ Error en la operación');

// Validación fallida
return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
💾 Operaciones CRUD (Modelos)
PHP

// Crear
$model->insert($datos);

// Actualizar
$model->update($id, $datos);

// Eliminar Físico (sin Soft Delete)
$model->delete($id);

// Buscar
$registro = $model->find($id);
$todos = $model->findAll();
🗃️ Transacciones
Utilizado en Profesores.php para eliminar el Profesor y su Usuario.

PHP

$db->transStart();
try {
    // Múltiples operaciones
    $db->transComplete();
} catch (\Exception $e) {
    $db->transRollback();
    // Manejar error (ej. log_message)
}