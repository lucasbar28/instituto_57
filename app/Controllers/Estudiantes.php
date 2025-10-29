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
        
        // Obtener los datos necesarios
        $estudiantes = $estudianteModel->findAll();
        $carreras = $carreraModel->findAll();
        $cursos = $cursoModel->findAll();

        // Mapear carreras por ID para fácil acceso en la vista
        $carreras_map = array_column($carreras, 'nombre_carrera', 'id_carrera');

        // Obtener la lista de inscripciones (ID de inscripción, ID de alumno, ID de curso)
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
     * CORREGIDO: Se cambia 'dni' a 'dni_matricula' en la validación y al guardar.
     */
    public function guardar()
    {
        $estudianteModel = new EstudianteModel();
        $datos = $this->request->getPost();

        if (!$this->validate([
            // CORRECCIÓN CLAVE: La validación debe apuntar a la columna real 'alumnos.dni_matricula'
            'dni_matricula' => 'required|numeric|is_unique[alumnos.dni_matricula]',
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            // CORRECCIÓN CLAVE: La validación debe apuntar a la tabla correcta 'alumnos.email'
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

        $estudianteModel->insert([
            // CORRECCIÓN CLAVE: El dato que se inserta debe coincidir con el campo de la BD
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
            'carreras' => $carreraModel->findAll(),
            'validation' => \Config\Services::validation(),
            'page_title' => 'Editar Estudiante'
        ];

        return view('estudiantes_form', $data);
    }

    /**
     * Procesa el formulario de edición y actualiza el registro.
     * CORREGIDO: Se cambia 'dni' a 'dni_matricula' en la validación y al guardar.
     */
    public function actualizar()
    {
        $estudianteModel = new EstudianteModel();
        $datos = $this->request->getPost();
        // Asumo que el ID en el formulario es 'id_alumno' (clave primaria de la tabla 'alumnos')
        $id_estudiante = $datos['id_alumno']; 

        // Validar unicidad de DNI y Email, excluyendo el registro actual.
        if (!$this->validate([
            // CORRECCIÓN CLAVE: Usar dni_matricula y el nombre de la clave primaria 'id_alumno'
            'dni_matricula' => "required|numeric|is_unique[alumnos.dni_matricula,id_alumno,{$id_estudiante}]",
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            // CORRECCIÓN CLAVE: Usar el nombre de la clave primaria 'id_alumno'
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

        $estudianteModel->update($id_estudiante, [
            // CORRECCIÓN CLAVE: El dato que se inserta debe coincidir con el campo de la BD
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