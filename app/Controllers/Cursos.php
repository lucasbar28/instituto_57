<?php

namespace App\Controllers;

// Importar los modelos necesarios
use App\Models\CursoModel;
use App\Models\ProfesorModel; // Necesario para el selector de profesor
use App\Models\CarreraModel;  // Necesario para el selector de carrera

class Cursos extends BaseController // Extender de BaseController por consistencia
{
    /**
     * Muestra la lista de todos los cursos.
     */
    public function index()
    {
        $cursoModel = new CursoModel();
        
        // NOTA: Idealmente, aquí se harían JOINs para obtener el nombre del profesor y la carrera.
        $data = [
            'cursos' => $cursoModel->findAll(),
            'page_title' => 'Lista de Cursos'
        ];

        return view('cursos', $data);
    }
    
    /**
     * Muestra el formulario para crear un nuevo curso, 
     * cargando datos de profesores y carreras.
     */
    public function crear()
    {
        // Instanciar modelos necesarios para los dropdowns
        $profesorModel = new ProfesorModel();
        $carreraModel = new CarreraModel();
        
        $data = [
            'profesores' => $profesorModel->findAll(), // Obtiene todos los profesores
            'carreras'   => $carreraModel->findAll(),  // Obtiene todas las carreras
            'validation' => \Config\Services::validation(), 
            'page_title' => 'Crear Curso'
        ];

        return view('cursos_form', $data); // Asumo que el formulario se llama 'cursos_form.php'
    }

    /**
     * Procesa los datos del formulario y guarda el nuevo curso.
     */
    public function guardar()
    {
        $cursoModel = new CursoModel();
        $datos = $this->request->getPost();

        // --- Reglas de Validación ---
        if (! $this->validate([
            // Nombre del curso debe ser obligatorio y único en la tabla 'cursos'
            'nombre_curso' => 'required|min_length[3]|is_unique[cursos.nombre_curso]', 
            'creditos'     => 'required|integer|greater_than[0]',
            'id_profesor'  => 'required|integer', // ID del profesor
            'id_carrera'   => 'required|integer', // ID de la carrera
            'descripcion'  => 'max_length[500]',  // Descripción es opcional, pero limitada
        ], 
        // Mensajes personalizados
        [
            'nombre_curso' => [
                'is_unique' => 'Ya existe un curso con este nombre. Por favor, ingrese un nombre diferente.'
            ]
        ])) {
            // Si la validación falla, regresa al formulario con los errores y datos
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- Inserción de Datos ---
        $cursoModel->insert([
            'nombre_curso' => $datos['nombre_curso'],
            'creditos'     => $datos['creditos'],
            'id_profesor'  => $datos['id_profesor'],
            'id_carrera'   => $datos['id_carrera'],
            'descripcion'  => $datos['descripcion'] ?? null, // Usa null si no se proporciona
        ]);

        // Redirección con mensaje de éxito (flash data)
        return redirect()->to(base_url('cursos'))->with('mensaje', '✅ Curso registrado con éxito!');
    }
}
 