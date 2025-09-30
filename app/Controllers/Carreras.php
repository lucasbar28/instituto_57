<?php

namespace App\Controllers;

// Importar los modelos que vamos a necesitar
use App\Models\CarreraModel;
use App\Models\CategoriaModel;

class Carreras extends BaseController
{
    /**
     * Muestra la lista de todas las carreras (placeholder por ahora).
     */
    public function index()
    {
        $carreraModel = new CarreraModel();
        $data = [
            'carreras' => $carreraModel->findAll(),
            'page_title' => 'Lista de Carreras'
        ];
        
        // Simplemente muestra una página de 'éxito' para fines de prueba/redirección
        return view('carreras_list', $data); 
    }
    
    /**
     * Prepara y muestra el formulario para crear una nueva carrera.
     */
    public function crear()
    {
        // 1. Instanciar el modelo de Categorías
        $categoriaModel = new CategoriaModel();
        
        // 2. Obtener TODAS las categorías de la base de datos
        $categorias = $categoriaModel->findAll();

        // 3. Preparar los datos para la vista
        $data = [
            'categorias' => $categorias,
            'validation' => \Config\Services::validation(), // Para mensajes de validación
            'page_title' => 'Crear Carrera'
        ];

        // 4. Cargar y mostrar la vista
        return view('carreras_form', $data);
    }
    
    /**
     * Procesa los datos del formulario y guarda la nueva carrera.
     */
    public function guardar()
    {
        $carreraModel = new CarreraModel();
        $datos = $this->request->getPost();

        // --- Reglas de Validación ---
        // Estas reglas garantizan que los datos son obligatorios y tienen el formato correcto
        if (! $this->validate([
            'nombre_carrera' => 'required|min_length[3]',
            'duracion'       => 'required|integer|greater_than[0]',
            'modalidad'      => 'required',
            'id_categoria'   => 'required|integer',
        ])) {
            // Si la validación falla, regresa al formulario con los errores
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- Inserción de Datos ---
        $carreraModel->insert([
            'nombre_carrera' => $datos['nombre_carrera'],
            'duracion'       => $datos['duracion'],
            'modalidad'      => $datos['modalidad'],
            'id_categoria'   => $datos['id_categoria'],
            // 'estado' y 'fecha_creacion' deben ser manejados por el modelo o la DB
        ]);

        // Redirección con mensaje de éxito (flash data)
        return redirect()->to(base_url('carreras'))->with('mensaje', '✅ ¡Carrera registrada con éxito!');
    }
} 