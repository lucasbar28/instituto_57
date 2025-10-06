<?php

namespace App\Controllers;

use App\Models\CarreraModel; // Necesitas este modelo para el dropdown
use App\Models\EstudianteModel; // Aunque la tabla es 'alumnos'
use CodeIgniter\Controller;

class Estudiantes extends BaseController
{
    /**
     * Muestra la lista de todos los estudiantes (alumnos).
     */
    public function index()
    {
        $estudianteModel = new EstudianteModel();
        
        $data = [
            'estudiantes' => $estudianteModel->findAll(),
            'title'       => 'Lista de Alumnos', // Título para el layout
        ];
        
        // Carga la vista de lista (que ya corregiste y debes adaptar al layout)
        return view('estudiantes', $data); 
    }
    
    /**
     * Prepara y muestra el formulario para crear un nuevo estudiante.
     */
    public function crear()
    {
        // 1. Instanciar el modelo de Carreras
        $carreraModel = new CarreraModel();
        
        // 2. Preparar los datos necesarios para la vista
        $data = [
            'carreras'   => $carreraModel->findAll(), // Obtiene todas las carreras
            'validation' => \Config\Services::validation(), // Para mensajes de validación
            'title'      => 'Registrar Nuevo Alumno', // Título para el layout (head.php)
        ];

        // 3. Cargar el formulario
        // Nota: La vista 'estudiantes_form' debe usar $this->extend('templates/layout')
        return view('estudiantes_form', $data);
    }
    
    /**
     * Procesa los datos del formulario y guarda el nuevo estudiante.
     */
    public function guardar()
    {
        $estudianteModel = new EstudianteModel();
        $datos = $this->request->getPost();

        // --- Reglas de Validación ---
        if (! $this->validate([
            'nombre_completo' => 'required|min_length[3]',
            'dni_matricula'   => 'required|is_unique[alumnos.dni_matricula]', // Asumiendo que es único
            'email'           => 'required|valid_email|is_unique[alumnos.email]',
            'id_carrera'      => 'required|integer',
        ])) {
            // Si la validación falla, regresa al formulario con los errores y los datos ingresados
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- Inserción de Datos en la tabla 'alumnos' ---
        $estudianteModel->insert([
            'nombre_completo' => $datos['nombre_completo'],
            'dni_matricula'   => $datos['dni_matricula'],
            'email'           => $datos['email'],
            'telefono'        => $datos['telefono'],
            'id_carrera'      => $datos['id_carrera'],
        ]);

        // Redirección exitosa a la lista de alumnos
        return redirect()->to(base_url('estudiantes'))->with('mensaje', '✅ Alumno registrado con éxito!');
    }
} 