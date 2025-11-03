<?php

namespace App\Controllers;

use App\Models\CursoModel;
use App\Models\ProfesorModel;
use App\Models\CarreraModel;

class Cursos extends BaseController
{
    protected $cursoModel;
    protected $profesorModel; // Añadido
    protected $carreraModel; // Añadido

    public function __construct()
    {
        $this->cursoModel = new CursoModel();
        // CORRECCIÓN: Instanciar todos los modelos necesarios
        $this->profesorModel = new ProfesorModel();
        $this->carreraModel = new CarreraModel();
    }

    /**
     * Carga las listas de profesores y carreras para los dropdowns.
     */
    protected function loadDropdownData()
    {
        return [
            'profesores' => $this->profesorModel->findAll(), 
            'carreras'   => $this->carreraModel->findAllActive(), 
        ];
    }
    // ------------------------------------------

    /**
     * Muestra la lista de todos los cursos activos, con los nombres relacionados.
     */
    public function index()
    {
        $data = [
            // CORRECCIÓN: Llamar a la función que SÍ existe en el Modelo
            'cursos' => $this->cursoModel->findAllWithRelations(),
            'page_title' => 'Lista de Cursos'
        ];

        return view('cursos', $data); // Asumiendo 'cursos.php'
    }
    
    /**
     * Muestra el formulario para crear un nuevo curso.
     */
    public function crear()
    {
        $data = $this->loadDropdownData();
        $data['validation'] = \Config\Services::validation(); 
        $data['page_title'] = 'Crear Curso';
        $data['curso'] = null; 

        return view('cursos_form', $data);
    }

    /**
     * Procesa los datos del formulario y guarda el nuevo curso.
     */
    public function guardar()
    {
        $datos = $this->request->getPost();

        // --- CORRECCIÓN DE VALIDACIÓN ---
        // Se elimina 'id_profesor' (no existe en la BD)
        if (! $this->validate([
            'nombre'        => 'required|min_length[3]|is_unique[cursos.nombre]', 
            'codigo'        => 'required|max_length[10]|is_unique[cursos.codigo]',
            'creditos'      => 'required|integer|greater_than[0]',
            'cupo_maximo'   => 'required|integer|greater_than[0]',
            'id_carrera'    => 'required|integer',
            'descripcion'   => 'max_length[500]',
        ])) {
            // CORRECCIÓN: Si la validación falla, debemos recargar los dropdowns
            $data = $this->loadDropdownData();
            $data['validation'] = $this->validator;
            $data['page_title'] = 'Crear Curso';
            $data['curso'] = null; // Modo creación
            return view('cursos_form', $data); // No usamos redirect()->back()
        }

        // --- CORRECCIÓN DE INSERCIÓN ---
        // Se elimina 'id_profesor'
        $this->cursoModel->insert([
            'nombre'        => $datos['nombre'],
            'codigo'        => $datos['codigo'],
            'creditos'      => $datos['creditos'],
            'cupo_maximo'   => $datos['cupo_maximo'],
            // 'id_profesor'   => $datos['id_profesor'], // ELIMINADO
            'id_carrera'    => $datos['id_carrera'],
            'descripcion'   => $datos['descripcion'] ?? null,
        ]);

        return redirect()->to(base_url('cursos'))->with('mensaje', '✅ Curso registrado con éxito! Se creó el archivo JSON.');
    }

    /**
     * Muestra el formulario con los datos de un curso para editar.
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
        $id = $datos['id_curso']; 

        // --- CORRECCIÓN DE VALIDACIÓN ---
        // Se elimina 'id_profesor'
        if (! $this->validate([
            'nombre'        => "required|min_length[3]|is_unique[cursos.nombre,id_curso,{$id}]", 
            'codigo'        => "required|max_length[10]|is_unique[cursos.codigo,id_curso,{$id}]",
            'creditos'      => 'required|integer|greater_than[0]',
            'cupo_maximo'   => 'required|integer|greater_than[0]',
            // 'id_profesor'   => 'required|integer', // ELIMINADO
            'id_carrera'    => 'required|integer',
            'descripcion'   => 'max_length[500]',
        ])) {
            // CORRECCIÓN: Si la validación falla, debemos recargar los dropdowns
            $data = $this->loadDropdownData();
            $data['validation'] = $this->validator;
            $data['page_title'] = 'Editar Curso';
            $data['curso'] = $this->cursoModel->find($id); // Recargar datos originales
            return view('cursos_form', $data);
        }

        // --- CORRECCIÓN DE ACTUALIZACIÓN ---
        // Se elimina 'id_profesor'
        $this->cursoModel->update($id, [
            'nombre'        => $datos['nombre'],
            'codigo'        => $datos['codigo'],
            'creditos'      => $datos['creditos'],
            'cupo_maximo'   => $datos['cupo_maximo'],
            // 'id_profesor'   => $datos['id_profesor'], // ELIMINADO
            'id_carrera'    => $datos['id_carrera'],
            'descripcion'   => $datos['descripcion'] ?? null,
        ]);

        return redirect()->to(base_url('cursos'))->with('mensaje', '✅ Curso actualizado con éxito!');
    }

    /**
     * Ejecuta la eliminación lógica (Soft Delete) de un curso.
     */
    public function eliminar($id)
    {
        $curso = $this->cursoModel->find($id);

        if (!$curso) {
            return redirect()->to(base_url('cursos'))->with('error', '❌ Curso no encontrado.');
        }

        // CORRECTO: El modelo de Cursos SÍ usa Soft Delete
        $this->cursoModel->delete($id);

        return redirect()->to(base_url('cursos'))->with('mensaje', '🗑️ Curso "' . $curso['nombre'] . '" enviado a la papelera.');
    }
} 