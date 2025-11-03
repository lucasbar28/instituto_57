<?php

namespace App\Controllers;

use App\Models\EstudianteModel;
use App\Models\CarreraModel;
use App\Models\CursoModel;
use App\Models\InscripcionModel;

class Estudiantes extends BaseController
{
    /**
     * Muestra la lista de todos los estudiantes.
     */
    public function index()
    {
        $estudianteModel = new EstudianteModel();
        $carreraModel = new CarreraModel();
        $cursoModel = new CursoModel();
        $inscripcionModel = new InscripcionModel();
        
        $estudiantes = $estudianteModel->findAll();
        // Usamos el método del modelo que filtra por estado=1
        $carreras = $carreraModel->findAllActive(); 
        $cursos = $cursoModel->findAll();

        $carreras_map = array_column($carreras, 'nombre_carrera', 'id_carrera');

        // CORRECCIÓN: El modelo de Cursos usa 'nombre'
        $cursos_map = array_column($cursos, 'nombre', 'id_curso');

        $inscripciones_raw = $inscripcionModel->findAll(); // Model auto-filtra soft deletes

        $inscripciones_por_alumno = [];
        foreach ($inscripciones_raw as $inscripcion) {
            // CORRECCIÓN: Usar 'nombre' (el alias 'nombre_curso' ya no se usa en el Modelo simple)
            $curso_nombre = $cursos_map[$inscripcion['id_curso']] ?? 'Curso Desconocido'; 
            
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
            'cursos' => $cursos, 
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
            'carreras' => $carreraModel->findAllActive(), // Solo mostrar carreras activas
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

        // VALIDACIÓN (Usa 'dni_matricula' y 'alumnos')
        if (!$this->validate([
            'dni_matricula' => 'required|numeric|is_unique[alumnos.dni_matricula]',
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|is_unique[alumnos.email]',
            'id_carrera' => 'required|integer',
        ],
        [
            'dni_matricula' => [
                'is_unique' => 'Ya existe un estudiante con este DNI/Matrícula.'
            ],
            'email' => [
                'is_unique' => 'Ya existe un estudiante con este correo electrónico.'
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // INSERCIÓN (Usa 'dni_matricula')
        $estudianteModel->insert([
            'dni_matricula' => $datos['dni_matricula'], 
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
            'carreras' => $carreraModel->findAllActive(), // Solo carreras activas
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
        
        // CORRECCIÓN: El ID se llama 'id_alumno' en el modelo
        $id_estudiante = $datos['id_alumno']; 

        // VALIDACIÓN (Usa 'dni_matricula' y 'id_alumno')
        if (!$this->validate([
            'dni_matricula' => "required|numeric|is_unique[alumnos.dni_matricula,id_alumno,{$id_estudiante}]",
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            'email' => "required|valid_email|is_unique[alumnos.email,id_alumno,{$id_estudiante}]",
            'id_carrera' => 'required|integer',
        ],
        [
            'dni_matricula' => [
                'is_unique' => 'Ya existe un estudiante con este DNI/Matrícula.'
            ],
            'email' => [
                'is_unique' => 'Ya existe un estudiante con este correo electrónico.'
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // ACTUALIZACIÓN (Usa 'dni_matricula')
        $estudianteModel->update($id_estudiante, [
            'dni_matricula' => $datos['dni_matricula'], 
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
            // Eliminación Física (Hard Delete)
            $estudianteModel->delete($id);
            return redirect()->to(base_url('estudiantes'))->with('mensaje', '🗑️ Estudiante eliminado con éxito!');
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), '1451') !== false) {
                 return redirect()->to(base_url('estudiantes'))->with('error', '❌ Error: No se puede eliminar el estudiante porque tiene inscripciones asociadas (FK constraint). Desinscriba al estudiante de todos los cursos primero.');
            }
            return redirect()->to(base_url('estudiantes'))->with('error', '❌ Error al eliminar el estudiante: ' . $e->getMessage());
        }
    }
} 