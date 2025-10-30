🎮 CONTROLADORES - Sistema Académico
🏗️ ESTRUCTURA BASE
🎛️ BaseController (BaseController.php)
php
abstract class BaseController extends Controller
{
    protected $helpers = ['url'];
    
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }
}
🏠 Home (Home.php)
Controlador de página principal
php
class Home extends BaseController
{
    public function index(): string
    {
        return view('home');
    }
}
🔐 Login (Login.php)
Sistema de autenticación
Métodos:
index() - Muestra formulario de login

auth() - Procesa autenticación

logout() - Cierra sesión

Flujo de Autenticación:
php
public function auth()
{
    $model = new UsuarioModel();
    $email = $this->request->getVar('nombre_de_usuario');
    $password = $this->request->getVar('contrasena');
    
    // 1. Buscar usuario
    $usuario = $model->where('nombre_de_usuario', $email)->first();
    
    // 2. Verificar contraseña
    if ($usuario && password_verify($password, $usuario['contrasena'])) {
        // 3. Crear sesión
        $sesionData = [
            'id_usuario' => $usuario['id_usuario'],
            'username' => $usuario['nombre_de_usuario'],
            'rol' => $usuario['rol'],
            'isLoggedIn' => TRUE
        ];
        session()->set($sesionData);
        return redirect()->to('/');
    }
    
    return redirect()->back()->with('msg', 'Credenciales inválidas');
}
🎓 Carreras (Carreras.php)
Gestión completa de carreras
Métodos CRUD:
index() - Lista carreras activas

crear() - Formulario de creación

guardar() - Procesa creación

editar($id) - Formulario de edición

actualizar() - Procesa actualización

eliminar($id) - Eliminación lógica

Ejemplo Guardar:
php
public function guardar()
{
    $carreraModel = new CarreraModel();
    
    if (!$this->validate([
        'nombre_carrera' => 'required|min_length[3]|is_unique[carreras.nombre_carrera]',
        'duracion' => 'required|integer|greater_than[0]',
        'modalidad' => 'required',
        'id_categoria' => 'required|integer',
    ])) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }
    
    $carreraModel->insert($this->request->getPost());
    return redirect()->to('carreras')->with('mensaje', '✅ Carrera registrada');
}
🏷️ Categorias (Categorias.php)
Gestión de categorías
Métodos:
index() - Lista todas las categorías

crear($id=null) - Formulario dual (crear/editar)

guardar() - Guarda o actualiza

eliminar($id) - Eliminación física

Formulario Dual:
php
public function crear($id = null)
{
    $model = new CategoriaModel();
    $categoria = $id ? $model->find($id) : null;
    
    $data = [
        'validation' => \Config\Services::validation(),
        'page_title' => $id ? 'Editar Categoría' : 'Crear Categoría',
        'categoria' => $categoria,
        'id' => $id
    ];
    return view('categorias_form', $data);
}
📚 Cursos (Cursos.php)
Gestión de cursos académicos
Métodos Helper:
php
protected function findAllWithRelations()
{
    return $this->cursoModel
        ->select('cursos.*, p.nombre_completo as nombre_profesor, c.nombre_carrera')
        ->join('profesores p', 'p.id_profesor = cursos.id_profesor', 'left')
        ->join('carreras c', 'c.id_carrera = cursos.id_carrera', 'left')
        ->findAll();
}

protected function loadDropdownData()
{
    return [
        'profesores' => (new ProfesorModel())->findAll(),
        'carreras' => (new CarreraModel())->findAllActive(),
    ];
}
Métodos CRUD:
index() - Lista cursos con relaciones

crear() - Formulario con dropdowns

guardar() - Crea nuevo curso

editar($id) - Formulario de edición

actualizar() - Actualiza curso

eliminar($id) - Soft delete

👨‍🎓 Estudiantes (Estudiantes.php)
Gestión de estudiantes con inscripciones
Métodos CRUD:
index() - Lista estudiantes + datos relacionados

crear() - Formulario de creación

guardar() - Procesa creación

editar($id) - Formulario de edición

actualizar() - Procesa actualización

eliminar($id) - Eliminación física

Estructura de Datos en Index:
php
public function index()
{
    $estudianteModel = new EstudianteModel();
    $carreraModel = new CarreraModel();
    $cursoModel = new CursoModel();
    $inscripcionModel = new InscripcionModel();
    
    // Mapeo de datos para vista
    $carreras_map = array_column($carreras, 'nombre_carrera', 'id_carrera');
    $inscripciones_por_alumno = [];
    
    $data = [
        'estudiantes' => $estudianteModel->findAll(),
        'carreras_map' => $carreras_map,
        'cursos' => $cursoModel->findAll(),
        'inscripciones_por_alumno' => $inscripciones_por_alumno
    ];
    
    return view('estudiantes', $data);
}
👨‍🏫 Profesores (Profesores.php)
Gestión de profesores con usuarios
Características:
Transacciones para consistencia

Creación dual (profesor + usuario)

Generación automática de contraseñas

Método Guardar con Transacción:
php
public function guardar()
{
    $profesorModel = new ProfesorModel();
    $usuarioModel = new UsuarioModel();
    $db = \Config\Database::connect();
    
    $datos = $this->request->getPost();
    
    // Validación
    if (!$this->validate([...])) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }
    
    // Generar contraseña temporal
    $contrasena_inicial = bin2hex(random_bytes(8));
    
    $db->transStart();
    try {
        // 1. Crear usuario
        $id_usuario = $usuarioModel->insert([
            'nombre_de_usuario' => $datos['email'],
            'contrasena' => password_hash($contrasena_inicial, PASSWORD_DEFAULT),
            'rol' => 'profesor',
            'estado' => 'activo'
        ]);
        
        // 2. Crear profesor
        $profesorModel->insert([
            'nombre_completo' => $datos['nombre_completo'],
            'especialidad' => $datos['especialidad'],
            'email' => $datos['email'],
            'telefono' => $datos['telefono'],
            'id_usuario' => $id_usuario
        ]);
        
        $db->transComplete();
        
        return redirect()->to('profesores')->with('mensaje', 
            '✅ Profesor creado. Credencial: ' . $datos['email'] . ' | Contraseña: ' . $contrasena_inicial);
            
    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->back()->withInput()->with('error', '❌ Error: ' . $e->getMessage());
    }
}
Métodos Completos:
index() - Lista profesores

crear() - Formulario de registro

guardar() - Crea profesor + usuario

editar($id) - Formulario de edición

actualizar() - Actualiza ambos registros

eliminar($id) - Elimina profesor + usuario

📝 Inscripcion (Inscripcion.php)
Gestión de inscripciones
Métodos:
inscribir() - Crea nueva inscripción (POST)

desinscribir($id_alumno) - Soft delete de última inscripción (GET)

Método Inscribir:
php
public function inscribir()
{
    $data = $this->request->getPost(['id_alumno', 'id_curso']);
    
    $dataToSave = [
        'id_alumno' => $data['id_alumno'],
        'id_curso' => $data['id_curso'],
        'fecha_inscripcion' => Time::now()->toDateString(),
        'estado' => 'Activo'
    ];
    
    if ($this->inscripcionModel->insert($dataToSave)) {
        return redirect()->to('estudiantes')->with('mensaje', '✅ Alumno inscrito');
    }
    
    return redirect()->back()->withInput()->with('error', '❌ Error al inscribir');
}
Método Desinscribir:
php
public function desinscribir(int $id_alumno)
{
    // Buscar última inscripción activa
    $ultimaInscripcion = $this->inscripcionModel
        ->where('id_alumno', $id_alumno)
        ->where('deleted_at IS NULL')
        ->orderBy('fecha_inscripcion', 'DESC')
        ->first();
        
    if ($ultimaInscripcion) {
        $this->inscripcionModel->delete($ultimaInscripcion['id_inscripcion']);
        return redirect()->to('estudiantes')->with('mensaje', '✅ Desinscripción realizada');
    }
    
    return redirect()->to('estudiantes')->with('error', '❌ No hay inscripciones activas');
}
🔄 PATRONES COMUNES
✅ Validaciones
php
if (!$this->validate([
    'campo' => 'required|min_length[3]|is_unique[tabla.campo]',
])) {
    return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
}
🔄 Redirecciones con Mensajes
php
// Éxito
return redirect()->to('entidad')->with('mensaje', '✅ Operación exitosa');

// Error  
return redirect()->back()->withInput()->with('error', '❌ Error en la operación');

// Validación fallida
return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
💾 Operaciones CRUD
php
// Crear
$model->insert($datos);

// Actualizar
$model->update($id, $datos);

// Eliminar
$model->delete($id);

// Buscar
$registro = $model->find($id);
$todos = $model->findAll();
🗃️ Transacciones
php
$db->transStart();
try {
    // Múltiples operaciones
    $db->transComplete();
} catch (\Exception $e) {
    $db->transRollback();
    // Manejar error
}
📊 RESUMEN DE CONTROLADORES
Controlador	Métodos Principales	Característica Especial
Home	index	Página principal simple
Login	index, auth, logout	Autenticación y sesiones
Carreras	CRUD completo	Eliminación lógica
Categorias	CRUD completo	Formulario dual
Cursos	CRUD completo	Soft delete, relaciones JOIN
Estudiantes	CRUD completo	Gestión de inscripciones integrada
Profesores	CRUD completo	Transacciones, creación dual
Inscripcion	inscribir, desinscribir	Gestión específica de inscripciones
