<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\Controller;
use CodeIgniter\Database\Exceptions\DatabaseException; 

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
            'validation' => \Config\Services::validation(),
            'page_title' => 'Crear Categoría',
            'categoria'  => null, // Modo Creación
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

        // Validamos usando las reglas del Modelo (is_unique)
        if (!$this->validate(
            ['nombre' => 'required|min_length[3]|max_length[100]|is_unique[categorias.nombre]'],
            ['nombre' => ['is_unique' => 'Ya existe una categoría con este nombre.']]
        )) {
            // Si la validación falla, regresa al formulario con los datos y errores
            $data = [
                'validation' => $this->validator,
                'page_title' => 'Crear Categoría',
                'categoria'  => null,
            ];
            return view('categorias_form', $data);
            // return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $datos_a_guardar = [
            'nombre'      => $datos['nombre'],
            'descripcion' => $datos['descripcion'], 
        ];

        $categoriaModel->insert($datos_a_guardar);
        
        return redirect()->to(base_url('categorias'))->with('mensaje', '✅ Categoría registrada con éxito!');
    }

    /**
     * Muestra el formulario para editar una categoría.
     */
    public function editar($id)
    {
        $model = new CategoriaModel();
        $categoria = $model->find($id);

        if (!$categoria) {
            return redirect()->to(base_url('categorias'))->with('error', '❌ Categoría no encontrada.');
        }

        $data = [
            'validation' => \Config\Services::validation(),
            'page_title' => 'Editar Categoría',
            'categoria'  => $categoria, // Pasa los datos para edición
        ];

        return view('categorias_form', $data);
    }

    /**
     * Procesa la actualización de una categoría.
     */
    public function actualizar()
    {
        $categoriaModel = new CategoriaModel();
        $datos = $this->request->getPost();
        $id = $datos['id_categoria']; // Asumimos campo oculto

        // Validamos (ignorando el ID actual en 'is_unique')
        if (!$this->validate(
            ['nombre' => "required|min_length[3]|max_length[100]|is_unique[categorias.nombre,id_categoria,{$id}]"],
            ['nombre' => ['is_unique' => 'Ya existe una categoría con este nombre.']]
        )) {
            $data = [
                'validation' => $this->validator,
                'page_title' => 'Editar Categoría',
                'categoria'  => $categoriaModel->find($id),
            ];
            return view('categorias_form', $data);
            // return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $datos_a_guardar = [
            'nombre'      => $datos['nombre'],
            'descripcion' => $datos['descripcion'], 
        ];

        $categoriaModel->update($id, $datos_a_guardar);
        
        return redirect()->to(base_url('categorias'))->with('mensaje', '✅ Categoría actualizada con éxito!');
    }


    /**
     * Elimina una categoría por su ID.
     */
    public function eliminar($id = null)
    {
        $categoriaModel = new CategoriaModel();

        try {
            if ($categoriaModel->delete($id)) {
                 return redirect()->to(base_url('categorias'))->with('mensaje', '🗑️ Categoría eliminada con éxito!');
            } else {
                 return redirect()->to(base_url('categorias'))->with('error', '❌ Error: Categoría no encontrada o no se pudo eliminar.');
            }
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), '1451') !== false) {
                 return redirect()->to(base_url('categorias'))->with('error', '❌ Error: No se puede eliminar la categoría porque tiene Carreras asociadas. Desasocie las Carreras primero.');
            }

            log_message('error', 'Error al eliminar categoría: ' . $e->getMessage());
            return redirect()->to(base_url('categorias'))->with('error', '❌ Error inesperado al intentar eliminar.');
        }
    }
} 