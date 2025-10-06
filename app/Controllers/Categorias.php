<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\Controller;

class Categorias extends BaseController
{
    /**
     * Muestra la lista de todas las categorías.
     */
    public function index()
    {
        $model = new CategoriaModel();
        
        $data = [
            'categorias' => $model->findAll(),
            'page_title' => 'Lista de Categorías'
        ];

        return view('categorias_list', $data); 
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function crear()
    {
        $data = [
            'validation' => \Config\Services::validation(), // Para mostrar errores de validación
            'page_title' => 'Crear Categoría'
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
            // Se añade 'is_unique' para evitar categorías duplicadas
            'nombre' => 'required|min_length[3]|max_length[100]|is_unique[categorias.nombre]',
            // 'descripcion' se mantiene opcional
        ], 
        // Mensajes personalizados
        [
            'nombre' => [
                'is_unique' => 'Ya existe una categoría con este nombre. Por favor, ingrese un nombre diferente.'
            ]
        ])) {
            // Si la validación falla, regresa al formulario con los datos y errores
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- Inserción de Datos ---
        $categoriaModel->insert([
            'nombre'      => $datos['nombre'],
            'descripcion' => $datos['descripcion'], 
        ]);

        // Redirección exitosa (redirige a la lista de categorías)
        return redirect()->to(base_url('categorias'))->with('mensaje', '✅ Categoría registrada con éxito!');
    }
}
 