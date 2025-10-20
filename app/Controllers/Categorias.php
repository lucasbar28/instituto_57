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

        // NOTA: Se asume que tu archivo de vista se llama 'categorias_list.php' o 'categorias/index.php'.
        // Si tu archivo se llama 'categorias.php' (como pusiste en el ejemplo de la vista), 
        // debes cambiar 'categorias_list' por 'categorias' aquí. Usaré 'categorias_list' como estándar.
        return view('categorias_list', $data); 
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     * También se usa como formulario de edición si se pasa un ID.
     */
    public function crear($id = null)
    {
        $model = new CategoriaModel();
        $categoria = $id ? $model->find($id) : null;

        $data = [
            'validation' => \Config\Services::validation(),
            'page_title' => $id ? 'Editar Categoría' : 'Crear Categoría',
            'categoria'  => $categoria, // Pasa los datos de la categoría si estamos editando
            'id'         => $id
        ];
        return view('categorias_form', $data);
    }

    /**
     * Procesa los datos del formulario y guarda la nueva categoría o actualiza una existente.
     */
    public function guardar()
    {
        $categoriaModel = new CategoriaModel();
        $datos = $this->request->getPost();
        $id = $datos['id_categoria'] ?? null; // Obtiene el ID si existe (para edición)

        // --- Reglas de Validación ---
        $regla_unicidad = $id ? 
            'required|min_length[3]|max_length[100]|is_unique[categorias.nombre,id_categoria,{id_categoria}]' : 
            'required|min_length[3]|max_length[100]|is_unique[categorias.nombre]';

        if (! $this->validate([
            'nombre' => $regla_unicidad, // Regla adaptada para evitar duplicados, excepto el actual
            'descripcion' => 'permit_empty' // Se mantiene opcional
        ], 
        [
            'nombre' => [
                'is_unique' => 'Ya existe una categoría con este nombre. Por favor, ingrese un nombre diferente.'
            ]
        ])) {
            // Si la validación falla, regresa al formulario con los datos y errores
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- Inserción/Actualización de Datos ---
        $datos_a_guardar = [
            'nombre'      => $datos['nombre'],
            'descripcion' => $datos['descripcion'], 
        ];

        if ($id) {
            $categoriaModel->update($id, $datos_a_guardar);
            $mensaje = '✅ Categoría actualizada con éxito!';
        } else {
            $categoriaModel->insert($datos_a_guardar);
            $mensaje = '✅ Categoría registrada con éxito!';
        }

        // Redirección exitosa (redirige a la lista de categorías)
        return redirect()->to(base_url('categorias'))->with('mensaje', $mensaje);
    }

    /**
     * Elimina una categoría por su ID.
     */
    public function eliminar($id = null)
    {
        $categoriaModel = new CategoriaModel();

        if ($categoriaModel->delete($id)) {
             // Redirección exitosa
             return redirect()->to(base_url('categorias'))->with('mensaje', '🗑️ Categoría eliminada con éxito!');
        } else {
             // Manejo de error si no se encuentra o no se puede eliminar
             return redirect()->to(base_url('categorias'))->with('error', '❌ Error al eliminar la categoría o no se encontró el registro.');
        }
    }
}
 