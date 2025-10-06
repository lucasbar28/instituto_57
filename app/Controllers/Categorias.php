<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\Controller;

class Categorias extends BaseController
{
    /**
     * Muestra una vista por defecto (lista de categorías).
     */
    public function index()
    {
        $model = new CategoriaModel();
        $data['categorias'] = $model->findAll();

        // Podrías crear una vista 'categorias_list' para mostrar los datos
        return view('categorias_list', $data); 
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     * Carga simplemente la vista 'categorias_form.php'.
     */
    public function crear()
    {
        $data = [
            'validation' => \Config\Services::validation(), // Para mostrar errores de validación
        ];
        return view('categorias_form', $data);
    }

    /**
     * Procesa los datos del formulario y guarda la nueva categoría en la DB.
     */
    public function guardar()
    {
        $categoriaModel = new CategoriaModel();
        $datos = $this->request->getPost();

        // --- Reglas de Validación ---
        if (! $this->validate([
            'nombre' => 'required|min_length[3]|max_length[100]',
            // 'descripcion' es opcional, así que no se requiere
        ])) {
            // Si la validación falla, regresa al formulario con los datos y errores
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- Inserción de Datos ---
        $categoriaModel->insert([
            'nombre'      => $datos['nombre'],
            'descripcion' => $datos['descripcion'], // Puede ser NULL si el campo estaba vacío
        ]);

        // Redirección exitosa (redirige a la lista de categorías)
        return redirect()->to(base_url('categorias'))->with('mensaje', '✅ Categoría registrada con éxito!');
    }
} 