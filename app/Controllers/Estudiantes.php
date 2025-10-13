<?php

namespace App\Controllers;

use App\Models\EstudianteModel;
use App\Models\CarreraModel;
use App\Models\CursoModel; // Nuevo: Para obtener la lista de cursos
use App\Models\InscripcionModel; // Nuevo: Para obtener las inscripciones

class Estudiantes extends BaseController
{
    /**
     * Muestra la lista de todos los estudiantes.
     * Ahora también obtiene la lista de cursos e inscripciones para la integración.
     */
    public function index()
    {
        $estudianteModel = new EstudianteModel();
        $carreraModel = new CarreraModel();
        $cursoModel = new CursoModel();
        $inscripcionModel = new InscripcionModel();
        
        // Obtener los datos necesarios
        $estudiantes = $estudianteModel->findAll();
        $carreras = $carreraModel->findAll();
        $cursos = $cursoModel->findAll();

        // Mapear carreras por ID para fácil acceso en la vista
        $carreras_map = array_column($carreras, 'nombre_carrera', 'id_carrera');

        // Obtener la lista de inscripciones (ID de inscripción, ID de alumno, ID de curso)
        // Usamos findAll() y luego mapeamos los nombres de los cursos.
        $inscripciones_raw = $inscripcionModel->findAll();

        // Mapear cursos por ID
        $cursos_map = array_column($cursos, 'nombre_curso', 'id_curso');

        // Estructurar las inscripciones por ID de alumno
        $inscripciones_por_alumno = [];
        foreach ($inscripciones_raw as $inscripcion) {
            $curso_nombre = $cursos_map[$inscripcion['id_curso']] ?? 'Curso Desconocido';
            
            // Si el ID de alumno es válido, añadir la inscripción
            if (isset($inscripcion['id_alumno'])) {
                $inscripciones_por_alumno[$inscripcion['id_alumno']][] = [
                    'id_inscripcion' => $inscripcion['id_inscripcion'],
                    'id_curso'       => $inscripcion['id_curso'],
                    'nombre_curso'   => $curso_nombre,
                    'fecha_inscripcion' => $inscripcion['fecha_inscripcion']
                ];
            }
        }

        $data = [
            'estudiantes' => $estudiantes,
            'carreras_map' => $carreras_map,
            'cursos' => $cursos, // Lista completa de cursos para el dropdown
            'inscripciones_por_alumno' => $inscripciones_por_alumno,
            'page_title' => 'Lista de Estudiantes'
        ];

        return view('estudiantes', $data);
    }
    
    /**
     * Muestra el formulario para crear un nuevo estudiante.
     */
    public function crear()
    {
        $carreraModel = new CarreraModel();

        $data = [
            'carreras' => $carreraModel->findAll(),
            'validation' => \Config\Services::validation(), 
            'page_title' => 'Registrar Nuevo Estudiante'
        ];

        return view('estudiantes_form', $data);
    }

    /**
     * Procesa los datos del formulario y guarda el nuevo estudiante.
     */
    public function guardar()
    {
        $estudianteModel = new EstudianteModel();
        $datos = $this->request->getPost();

        if (!$this->validate([
            'dni' => 'required|numeric|is_unique[estudiantes.dni]',
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|is_unique[estudiantes.email]',
            'id_carrera' => 'required|integer',
        ],
        [
            'dni' => [
                'is_unique' => 'Ya existe un estudiante con este DNI.'
            ],
            'email' => [
                'is_unique' => 'Ya existe un estudiante con este correo electrónico.'
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $estudianteModel->insert([
            'dni' => $datos['dni'],
            'nombre_completo' => $datos['nombre_completo'],
            'email' => $datos['email'],
            'id_carrera' => $datos['id_carrera'],
        ]);

        return redirect()->to(base_url('estudiantes'))->with('mensaje', '✅ Estudiante registrado con éxito!');
    }

    /**
     * Muestra el formulario para editar un estudiante.
     */
    public function editar($id)
    {
        $estudianteModel = new EstudianteModel();
        $carreraModel = new CarreraModel();
        
        $estudiante = $estudianteModel->find($id);

        if (empty($estudiante)) {
            return redirect()->to(base_url('estudiantes'))->with('error', '❌ Estudiante no encontrado.');
        }

        $data = [
            'estudiante' => $estudiante,
            'carreras' => $carreraModel->findAll(),
            'validation' => \Config\Services::validation(),
            'page_title' => 'Editar Estudiante'
        ];

        return view('estudiantes_form', $data);
    }

    /**
     * Procesa el formulario de edición y actualiza el registro.
     */
    public function actualizar()
    {
        $estudianteModel = new EstudianteModel();
        $datos = $this->request->getPost();
        $id_estudiante = $datos['id_estudiante'];

        // Validar unicidad de DNI y Email, excluyendo el registro actual.
        if (!$this->validate([
            'dni' => "required|numeric|is_unique[estudiantes.dni,id_estudiante,{$id_estudiante}]",
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            'email' => "required|valid_email|is_unique[estudiantes.email,id_estudiante,{$id_estudiante}]",
            'id_carrera' => 'required|integer',
        ],
        [
            'dni' => [
                'is_unique' => 'Ya existe un estudiante con este DNI.'
            ],
            'email' => [
                'is_unique' => 'Ya existe un estudiante con este correo electrónico.'
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $estudianteModel->update($id_estudiante, [
            'dni' => $datos['dni'],
            'nombre_completo' => $datos['nombre_completo'],
            'email' => $datos['email'],
            'id_carrera' => $datos['id_carrera'],
        ]);

        return redirect()->to(base_url('estudiantes'))->with('mensaje', '✅ Estudiante actualizado con éxito!');
    }

    /**
     * Elimina el registro del estudiante.
     */
    public function eliminar($id)
    {
        $estudianteModel = new EstudianteModel();

        try {
            $estudianteModel->delete($id);
            return redirect()->to(base_url('estudiantes'))->with('mensaje', '🗑️ Estudiante eliminado con éxito!');
        } catch (\Exception $e) {
            // Error de llave foránea (Foreign Key Constraint)
            if (strpos($e->getMessage(), '1451') !== false) {
                 return redirect()->to(base_url('estudiantes'))->with('error', '❌ Error: No se puede eliminar el estudiante porque tiene registros asociados (inscripciones). Desinscriba al estudiante de todos los cursos primero.');
            }
            return redirect()->to(base_url('estudiantes'))->with('error', '❌ Error al eliminar el estudiante: ' . $e->getMessage());
        }
    }
}
 