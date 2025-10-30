📚 MODELOS - Sistema Académico
🗃️ CarreraModel (CarreraModel.php)
Tabla: carreras

🎯 Características Principales:
Eliminación lógica mediante campo estado (1=activo, 0=inactivo)

Validación robusta con reglas específicas para modalidad y categorías

Callback automático para establecer estado por defecto

⚙️ Configuración:
php
protected $table              = 'carreras';
protected $primaryKey         = 'id_carrera';
protected $useAutoIncrement   = true;
protected $returnType         = 'array';
protected $useSoftDeletes     = false;
protected $allowedFields      = ['id_categoria', 'nombre_carrera', 'duracion', 'modalidad', 'estado'];
protected $useTimestamps      = false;
📋 Reglas de Validación:
php
protected $validationRules    = [
    'nombre_carrera' => 'required|min_length[3]|max_length[255]',
    'duracion'       => 'required|integer|greater_than_equal_to[1]',
    'modalidad'      => 'required|in_list[Presencial,Virtual,Mixta]',
    'id_categoria'   => 'required|integer|is_not_unique[categorias.id_categoria]',
    'estado'         => 'permit_empty|integer|in_list[0,1]',
];
🔧 Métodos Especiales:
php
/**
 * Callback para establecer estado predeterminado (1 - Activo)
 */
protected function setDefaultEstado(array $data)
{
    if (!isset($data['data']['estado'])) {
        $data['data']['estado'] = 1;
    }
    return $data;
}

/**
 * Obtener solo las carreras activas (estado = 1)
 */
public function findAllActive()
{
    return $this->where('estado', 1)->findAll();
}

/**
 * Eliminación lógica - actualiza el campo 'estado' a 0
 */
public function softDelete($id)
{
    return $this->update($id, ['estado' => 0]);
}
🗃️ CategoriaModel (CategoriaModel.php)
Tabla: categorias

🎯 Características Principales:
Timestamps personalizados (fecha_creacion, fecha_actualizacion)

Exportación automática a JSON después de cada inserción

Gestión de archivos en directorio writable/exports/

⚙️ Configuración:
php
protected $table      = 'categorias';
protected $primaryKey = 'id_categoria';
protected $allowedFields = ['nombre', 'descripcion'];
protected $useTimestamps = true;
protected $createdField  = 'fecha_creacion';
protected $updatedField  = 'fecha_actualizacion';
protected $dateFormat    = 'datetime';
🔄 Callbacks:
php
protected $afterInsert = ['guardarComoJSON'];

protected function guardarComoJSON(array $data)
{
    $id_insertado = $data['id'] ?? null;
    
    if ($id_insertado) {
        $registro = $this->find($id_insertado);
        $json_data = json_encode($registro, JSON_PRETTY_PRINT);
        
        $file_name = 'export_categoria_' . date('YmdHis') . '.json';
        $file_path = WRITEPATH . 'exports/' . $file_name;
        
        if (!is_dir(WRITEPATH . 'exports')) {
            mkdir(WRITEPATH . 'exports', 0777, true);
        }
        
        file_put_contents($file_path, $json_data);
    }
    
    return $data;
}
🗃️ CursoModel (CursoModel.php)
Tabla: cursos

🎯 Características Principales:
Soft Deletes completo con deleted_at

Timestamps automáticos para creación y actualización

Estructura educativa con créditos y cupos máximos

⚙️ Configuración:
php
protected $table = 'cursos';
protected $primaryKey = 'id_curso';
protected $returnType = 'array';
protected $useSoftDeletes = true;
protected $createdField = 'created_at';
protected $updatedField = 'updated_at';
protected $deletedField = 'deleted_at';
protected $allowedFields = ['nombre', 'codigo', 'creditos', 'cupo_maximo', 'id_profesor', 'id_carrera', 'descripcion'];
protected $useTimestamps = true;
🗃️ EstudianteModel (EstudianteModel.php)
Tabla: alumnos

🎯 Características Principales:
Validación de unicidad para DNI y email

Exportación automática a JSON

Integración con usuarios mediante id_usuario

⚙️ Configuración:
php
protected $table        = 'alumnos';
protected $primaryKey   = 'id_alumno';
protected $allowedFields = ['nombre_completo', 'dni_matricula', 'email', 'telefono', 'id_usuario', 'id_carrera', 'estado'];
protected $useTimestamps = false;
📋 Reglas de Validación:
php
protected $validationRules = [
    'nombre_completo' => 'required|min_length[3]|max_length[100]',
    'dni_matricula'   => 'required|max_length[15]|is_unique[alumnos.dni_matricula,id_alumno,{id_alumno}]',
    'email'           => 'required|max_length[100]|valid_email|is_unique[alumnos.email,id_alumno,{id_alumno}]',
    'id_carrera'      => 'required|integer',
    'telefono'        => 'permit_empty|max_length[20]',
];
🔄 Callbacks:
php
protected $afterInsert = ['guardarComoJSON'];

protected function guardarComoJSON(array $data)
{
    if (isset($data['id']) && $data['id'] > 0) {
        $registro = $this->find($data['id']);
        $json_data = json_encode($registro, JSON_PRETTY_PRINT);
        
        $file_name = 'export_alumno_' . date('YmdHis') . '.json';
        $file_path = WRITEPATH . 'exports/' . $file_name;
        
        if (!is_dir(WRITEPATH . 'exports')) {
            mkdir(WRITEPATH . 'exports', 0777, true);
        }
        
        file_put_contents($file_path, $json_data);
    }
    return $data;
}
🗃️ InscripcionModel (InscripcionModel.php)
Tabla: inscripciones

🎯 Características Principales:
Gestión de relaciones entre alumnos y cursos

Mensajes de error personalizados

Timestamps estándar para seguimiento

⚙️ Configuración:
php
protected $table = 'inscripciones';
protected $primaryKey = 'id_inscripcion';
protected $allowedFields = ['id_alumno', 'id_curso', 'fecha_inscripcion', 'estado'];
protected $useTimestamps = true;
protected $createdField  = 'created_at';
protected $updatedField  = 'updated_at';
protected $useSoftDeletes = false;
📋 Reglas de Validación:
php
protected $validationRules = [
    'id_alumno' => 'required|integer',
    'id_curso'  => 'required|integer',
    'estado'    => 'required|string',
];
💬 Mensajes de Error Personalizados:
php
protected $validationMessages = [
    'id_alumno' => ['required' => 'El alumno es obligatorio para la inscripción.'],
    'id_curso' => ['required' => 'El curso es obligatorio para la inscripción.'],
];
🗃️ ProfesorModel (ProfesorModel.php)
Tabla: profesores

🎯 Características Principales:
Sin timestamps (tabla no tiene campos de fecha)

Exportación automática a JSON

Especialización académica integrada

⚙️ Configuración:
php
protected $table      = 'profesores';
protected $primaryKey = 'id_profesor';
protected $allowedFields = ['nombre_completo', 'especialidad', 'email', 'telefono', 'id_usuario'];
protected $useTimestamps = false;
🔄 Callbacks:
php
protected $afterInsert = ['guardarComoJSON'];

protected function guardarComoJSON(array $data)
{
    $id_insertado = $data['id'] ?? null;
    
    if ($id_insertado) {
        $registro = $this->find($id_insertado);
        
        if ($registro) {
            $json_data = json_encode($registro, JSON_PRETTY_PRINT);
            
            $file_name = 'export_profesor_' . date('YmdHis') . '.json';
            $file_path = WRITEPATH . 'exports/' . $file_name;
            
            if (!is_dir(WRITEPATH . 'exports')) {
                mkdir(WRITEPATH . 'exports', 0777, true);
            }
            
            file_put_contents($file_path, $json_data);
        }
    }
    
    return $data;
}
🗃️ UsuarioModel (UsuarioModel.php)
Tabla: usuarios

🎯 Características Principales:
Validación de email como nombre de usuario

Sistema de roles (admin, profesor, alumno)

Gestión de estados (activo/inactivo)

⚙️ Configuración:
php
protected $table      = 'usuarios';
protected $primaryKey = 'id';
protected $allowedFields = ['nombre_de_usuario', 'contrasena', 'rol', 'estado'];
protected $useTimestamps = false;
📋 Reglas de Validación:
php
protected $validationRules = [
    'contrasena'        => 'required',
    'nombre_de_usuario' => 'required|valid_email|is_unique[usuarios.nombre_de_usuario]',
    'rol'               => 'required|in_list[admin,profesor,alumno]',
    'estado'            => 'required|in_list[activo,inactivo]',
];
💬 Mensajes de Error Personalizados:
php
protected $validationMessages = [
    'nombre_de_usuario' => [
        'is_unique' => 'Este nombre de usuario (email) ya está registrado.',
        'valid_email' => 'El campo Email debe ser una dirección de correo válida.'
    ]
];
🔧 PATRONES COMUNES EN MODELOS
✅ Validaciones
Unicidad en campos críticos (email, DNI)

Listas controladas para modalidades, roles y estados

Validación de relaciones con is_not_unique

🔄 Callbacks Automáticos
setDefaultEstado - Valores por defecto

guardarComoJSON - Backup automático después de inserciones

📊 Gestión de Estados
Eliminación lógica en CarreraModel

Soft Deletes en CursoModel

Estados activo/inactivo en múltiples modelos

⏰ Timestamps
Personalizados en CategoriaModel

Estándar en CursoModel e InscripcionModel

Desactivados donde no aplica

🎯 ESTRUCTURA DE ARCHIVOS DE EXPORTACIÓN
text
writable/exports/
├── export_categoria_20231120143045.json
├── export_alumno_20231120143122.json
└── export_profesor_20231120143215.json
Cada exportación contiene el registro completo en formato JSON legible.

🔗 RELACIONES IMPLÍCITAS
Usuarios → Estudiantes/Profesores (through id_usuario)

Categorías → Carreras (through id_categoria)

Carreras → Cursos (through id_carrera)

Estudiantes → Inscripciones (through id_alumno)

Cursos → Inscripciones (through id_curso)