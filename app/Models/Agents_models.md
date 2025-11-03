📚 MODELOS - Sistema Académico 
🗃️ MODELOS DISPONIBLES
Modelo	Tabla	Característica Principal
CarreraModel	carreras	Eliminación lógica con campo estado
CategoriaModel	categorias	Exportación automática a JSON
CursoModel	cursos	Soft Deletes completo
EstudianteModel	alumnos	Validación de DNI/email únicos
InscripcionModel	inscripciones	Gestión de relaciones alumno-curso
ProfesorModel	profesores	Exportación automática a JSON
UsuarioModel	usuarios	Sistema de roles y autenticación
🔧 CONFIGURACIONES PRINCIPALES
⏰ Timestamps
Con Timestamps:

CategoriaModel - Campos personalizados: fecha_creacion, fecha_actualizacion

CursoModel - Campos estándar: created_at, updated_at

InscripcionModel - Campos estándar

Sin Timestamps:

CarreraModel, EstudianteModel, ProfesorModel, UsuarioModel

🗑️ Gestión de Borrados
Soft Deletes:

CursoModel - Usa deleted_at

Eliminación Lógica:

CarreraModel - Campo estado (1=activo, 0=inactivo)

Borrado Físico:

Resto de modelos

📋 VALIDACIONES DESTACADAS
🔒 Unicidad
php
// EstudianteModel
'dni_matricula' => 'is_unique[alumnos.dni_matricula]'
'email' => 'is_unique[alumnos.email]'

// UsuarioModel  
'nombre_de_usuario' => 'is_unique[usuarios.nombre_de_usuario]'
📝 Listas Controladas
php
// CarreraModel
'modalidad' => 'in_list[Presencial,Virtual,Mixta]'

// UsuarioModel
'rol' => 'in_list[admin,profesor,alumno]'
'estado' => 'in_list[activo,inactivo]'
🔄 CALLBACKS AUTOMÁTICOS
📤 Exportación JSON
Modelos con exportación:

CategoriaModel - Después de insertar

EstudianteModel - Después de insertar

ProfesorModel - Después de insertar

Ubicación archivos:

text
writable/exports/
├── export_categoria_20231120143045.json
├── export_alumno_20231120143122.json
└── export_profesor_20231120143215.json
⚙️ Configuraciones Automáticas
php
// CarreraModel - Estado por defecto
protected function setDefaultEstado($data)
{
    if (!isset($data['data']['estado'])) {
        $data['data']['estado'] = 1; // Activo
    }
    return $data;
}
🔗 RELACIONES IMPLÍCITAS
text
Usuarios → Estudiantes/Profesores (id_usuario)
Categorías → Carreras (id_categoria) 
Carreras → Cursos (id_carrera)
Estudiantes → Inscripciones (id_alumno)
Cursos → Inscripciones (id_curso)
Profesores → Cursos (id_profesor)
🎯 MÉTODOS ESPECIALES
CarreraModel
php
findAllActive() - Solo carreras con estado=1
softDelete($id) - Eliminación lógica (estado=0)
💾 ESTRUCTURA DE DATOS
Tablas con estado:

carreras - estado (1/0)

alumnos - estado

usuarios - estado

Tablas con relaciones:

inscripciones - id_alumno, id_curso

cursos - id_profesor, id_carrera

carreras - id_categoria

Campos únicos críticos:

alumnos.dni_matricula

alumnos.email

usuarios.nombre_de_usuario