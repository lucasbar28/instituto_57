<?php

namespace App\Controllers;

use App\Models\CursoModel;
use App\Models\ProfesorModel;
use App\Models\CarreraModel;

class Cursos extends BaseController
{
    protected $cursoModel;

    public function __construct()
    {
        $this->cursoModel = new CursoModel();
    }

    // --- Métodos de Ayuda para el Controlador ---
    
    /**
     * Extiende el modelo de Curso para incluir los nombres de la Carrera y el Profesor.
     * Si el CursoModel usa Soft Deletes, automáticamente filtrará por deleted_at IS NULL.
     */
    protected function findAllWithRelations()
    {
        // Se realiza un JOIN para obtener los nombres de las entidades relacionadas (Profesor y Carrera)
        // El findAll() del cursoModel automáticamente aplica el filtro de Soft Delete si está activo allí.
        return $this->cursoModel
            ->select('cursos.*, p.nombre_completo as nombre_profesor, c.nombre_carrera')
            ->join('profesores p', 'p.id_profesor = cursos.id_profesor', 'left')
            ->join('carreras c', 'c.id_carrera = cursos.id_carrera', 'left')
            ->findAll(); // Aquí, el modelo de cursos se encarga de filtrar por deleted_at si lo tiene activo
    }

    /**
     * Carga las listas de profesores y carreras para los dropdowns.
     * Se eliminan los filtros WHERE deleted_at IS NULL, ya que esa columna no existe en estas tablas.
     */
    protected function loadDropdownData()
    {
        $profesorModel = new ProfesorModel();
        $carreraModel = new CarreraModel();
        
        return [
            // El modelo de Profesor no tiene eliminación lógica, usamos findAll()
            'profesores' => $profesorModel->findAll(), 
            
            // El modelo de Carrera tiene su propia función de eliminación lógica por 'estado=0'
            // Usamos findAllActive() que ya filtra por 'estado=1'
            'carreras'   => $carreraModel->findAllActive(), 
        ];
    }
    // ------------------------------------------

    /**
     * Muestra la lista de todos los cursos activos, con los nombres relacionados.
     */
    public function index()
    {
        $data = [
            'cursos' => $this->findAllWithRelations(),
            'page_title' => 'Lista de Cursos'
        ];

        return view('cursos', $data);
    }
    
    /**
     * Muestra el formulario para crear un nuevo curso.
     */
    public function crear()
    {
        $data = $this->loadDropdownData();
        $data['validation'] = \Config\Services::validation(); 
        $data['page_title'] = 'Crear Curso';
        $data['curso'] = null; // Usado para que la vista de formulario sepa que es una creación

        return view('cursos_form', $data);
    }

    /**
     * Procesa los datos del formulario y guarda el nuevo curso.
     */
    public function guardar()
    {
        $datos = $this->request->getPost();

        // Reglas de Validación: El nombre y el código deben ser únicos
        if (! $this->validate([
            'nombre'        => 'required|min_length[3]|is_unique[cursos.nombre]', 
            'codigo'        => 'required|max_length[10]|is_unique[cursos.codigo]', // Validación de código
            'creditos'      => 'required|integer|greater_than[0]',
            'cupo_maximo'   => 'required|integer|greater_than[0]', // Validación de cupo
            'id_profesor'   => 'required|integer', 
            'id_carrera'    => 'required|integer',
            'descripcion'   => 'max_length[500]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->cursoModel->insert([
            'nombre'        => $datos['nombre'],
            'codigo'        => $datos['codigo'],
            'creditos'      => $datos['creditos'],
            'cupo_maximo'   => $datos['cupo_maximo'],
            'id_profesor'   => $datos['id_profesor'],
            'id_carrera'    => $datos['id_carrera'],
            'descripcion'   => $datos['descripcion'] ?? null,
        ]);

        return redirect()->to(base_url('cursos'))->with('mensaje', '✅ Curso registrado con éxito! Se creó el archivo JSON.');
    }

    /**
     * Muestra el formulario con los datos de un curso para editar.
     * @param int $id ID del curso a editar.
     */
    public function editar($id)
    {
        $curso = $this->cursoModel->find($id);

        if (!$curso) {
            return redirect()->to(base_url('cursos'))->with('error', '❌ Curso no encontrado.');
        }

        $data = $this->loadDropdownData();
        $data['validation'] = \Config\Services::validation();
        $data['page_title'] = 'Editar Curso: ' . $curso['nombre'];
        $data['curso'] = $curso;

        return view('cursos_form', $data);
    }

    /**
     * Procesa los datos del formulario para actualizar un curso existente.
     */
    public function actualizar()
    {
        $datos = $this->request->getPost();
        $id = $datos['id_curso']; // El ID del curso viene en un campo oculto del formulario

        // Regla de Validación de Nombre y Código: Ignorar el valor actual para la regla is_unique
        if (! $this->validate([
            'nombre'        => "required|min_length[3]|is_unique[cursos.nombre,id_curso,{$id}]", 
            'codigo'        => "required|max_length[10]|is_unique[cursos.codigo,id_curso,{$id}]",
            'creditos'      => 'required|integer|greater_than[0]',
            'cupo_maximo'   => 'required|integer|greater_than[0]',
            'id_profesor'   => 'required|integer', 
            'id_carrera'    => 'required|integer',
            'descripcion'   => 'max_length[500]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Actualización de Datos
        $this->cursoModel->update($id, [
            'nombre'        => $datos['nombre'],
            'codigo'        => $datos['codigo'],
            'creditos'      => $datos['creditos'],
            'cupo_maximo'   => $datos['cupo_maximo'],
            'id_profesor'   => $datos['id_profesor'],
            'id_carrera'    => $datos['id_carrera'],
            'descripcion'   => $datos['descripcion'] ?? null,
        ]);

        return redirect()->to(base_url('cursos'))->with('mensaje', '✅ Curso actualizado con éxito!');
    }

    /**
     * Ejecuta la eliminación lógica (Soft Delete) de un curso.
     * @param int $id ID del curso a eliminar.
     */
    public function eliminar($id)
    {
        $curso = $this->cursoModel->find($id);

        if (!$curso) {
            return redirect()->to(base_url('cursos'))->with('error', '❌ Curso no encontrado.');
        }

        // Ejecuta el Soft Delete (actualiza deleted_at)
        $this->cursoModel->delete($id);

        return redirect()->to(base_url('cursos'))->with('mensaje', '🗑️ Curso "' . $curso['nombre'] . '" eliminado lógicamente.');
    }
}
