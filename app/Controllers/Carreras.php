<?php

namespace App\Controllers;

// Importar los modelos que vamos a necesitar
use App\Models\CarreraModel;
use App\Models\CategoriaModel;

class Carreras extends BaseController
{
    /**
     * Muestra la lista de todas las carreras, posiblemente con sus categorías.
     */
    public function index()
    {
        $carreraModel = new CarreraModel();
        
        // NOTA: Si necesitas mostrar el nombre de la categoría, 
        // deberías hacer un JOIN o usar un método personalizado en el Modelo.
        // Por ahora, solo cargamos los datos básicos.
        $data = [
            'carreras' => $carreraModel->findAll(),
            'page_title' => 'Lista de Carreras'
        ];
        
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
            // Inyectar el servicio de validación para errores inline
            'validation' => \Config\Services::validation(), 
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
        if (! $this->validate([
            // Se agregó 'is_unique' para asegurar que el nombre de la carrera no se repita
            'nombre_carrera' => 'required|min_length[3]|is_unique[carreras.nombre_carrera]', 
            'duracion'       => 'required|integer|greater_than[0]',
            'modalidad'      => 'required',
            'id_categoria'   => 'required|integer',
        ], 
        // Mensajes personalizados para el campo único
        [
            'nombre_carrera' => [
                'is_unique' => 'Ya existe una carrera con este nombre. Por favor, ingrese un nombre diferente.'
            ]
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
            // Se asume que el estado se maneja por defecto en la base de datos o en el modelo
        ]);

        // Redirección con mensaje de éxito (flash data)
        return redirect()->to(base_url('carreras'))->with('mensaje', '✅ ¡Carrera registrada con éxito!');
    }
}
 