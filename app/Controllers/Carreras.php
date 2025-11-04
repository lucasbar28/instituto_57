<?php

namespace App\Controllers;

use App\Models\CarreraModel;
use App\Models\CategoriaModel;

class Carreras extends BaseController
{
    /**
     * Muestra la lista de todas las carreras activas (estado = 1).
     */
    public function index()
    {
        $carreraModel = new CarreraModel();
        
        $data = [
            'carreras' => $carreraModel->findAllActive(), 
            'page_title' => 'Lista de Carreras Activas'
        ];
        
        return view('carreras_list', $data); 
    }
    
    /**
     * Muestra el formulario para crear una nueva carrera.
     */
    public function crear()
    {
        $categoriaModel = new CategoriaModel();
        $categorias = $categoriaModel->findAll();

        $data = [
            'categorias' => $categorias,
            'validation' => \Config\Services::validation(), 
            'page_title' => 'Crear Carrera',
            'carrera' => null // Indica modo Creación
        ];

        return view('carreras_form', $data);
    }
    
    /**
     * Procesa los datos del formulario y guarda la nueva carrera.
     */
    public function guardar()
    {
        $carreraModel = new CarreraModel();
        $datos = $this->request->getPost();

        // Validamos usando las reglas del Modelo
        if (! $this->validate($carreraModel->validationRules)) {
            // Si la validación falla, regresa al formulario con los datos y errores
            // Necesitamos recargar las categorías para el dropdown
            $categoriaModel = new CategoriaModel();
            $data = [
                'categorias' => $categoriaModel->findAll(),
                'validation' => $this->validator, 
                'page_title' => 'Crear Carrera',
                'carrera' => null
            ];
            return view('carreras_form', $data);
            // return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); // Esto no pasa $categorias
        }

        // El estado se establece en 1 (Activa) por defecto gracias al callback 
        // 'setDefaultEstado' que definimos en el CarreraModel.
        $carreraModel->insert([
            'nombre_carrera' => $datos['nombre_carrera'],
            'duracion'       => $datos['duracion'],
            'modalidad'      => $datos['modalidad'],
            'id_categoria'   => $datos['id_categoria'],
        ]);

        return redirect()->to(base_url('carreras'))->with('mensaje', '✅ ¡Carrera registrada con éxito!');
    }

    /**
     * Muestra el formulario para editar una carrera existente.
     */
    public function editar($id)
    {
        $carreraModel = new CarreraModel();
        $categoriaModel = new CategoriaModel();
        
        $carrera = $carreraModel->find($id);

        if (!$carrera) {
            return redirect()->to(base_url('carreras'))->with('error', '❌ Carrera no encontrada.');
        }

        $categorias = $categoriaModel->findAll();

        $data = [
            'categorias' => $categorias,
            'carrera' => $carrera, 
            'validation' => \Config\Services::validation(), 
            'page_title' => 'Editar Carrera'
        ];

        return view('carreras_form', $data);
    }

    /**
     * Procesa los datos del formulario y actualiza la carrera existente.
     */
    public function actualizar()
    {
        $carreraModel = new CarreraModel();
        $datos = $this->request->getPost();
        
        // Asumimos que el ID viene en un campo oculto
        $id = $datos['id_carrera']; 

        // Copiamos las reglas de validación del modelo
        $validationRules = $carreraModel->validationRules;
        
        // Ajustamos la regla 'is_unique' para ignorar el ID actual
        $validationRules['nombre_carrera'] = "required|min_length[3]|is_unique[carreras.nombre_carrera,id_carrera,{$id}]";

        if (! $this->validate($validationRules)) {
            // Si hay error, volvemos al formulario con los datos y errores
            $categoriaModel = new CategoriaModel();
            $data = [
                'categorias' => $categoriaModel->findAll(),
                'carrera' => $carreraModel->find($id), // Recargar datos originales
                'validation' => $this->validator, 
                'page_title' => 'Editar Carrera'
            ];
            return view('carreras_form', $data);
            // return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Preparamos los datos base
        $data_to_update = [
            'nombre_carrera' => $datos['nombre_carrera'],
            'duracion'       => $datos['duracion'],
            'modalidad'      => $datos['modalidad'],
            'id_categoria'   => $datos['id_categoria'],
        ];

        // Solo actualizamos el estado si el campo viene del formulario
        if (isset($datos['estado'])) {
            $data_to_update['estado'] = $datos['estado'];
        }

        $carreraModel->update($id, $data_to_update);

        return redirect()->to(base_url('carreras'))->with('mensaje', '✅ ¡Carrera actualizada con éxito!');
    }

    /**
     * Realiza la eliminación lógica (cambio de estado a 0) de una carrera.
     */
    public function eliminar($id)
    {
        $carreraModel = new CarreraModel();

        $carrera = $carreraModel->find($id);

        if (!$carrera) {
            return redirect()->to(base_url('carreras'))->with('error', '❌ Carrera no encontrada.');
        }

        // USANDO EL MÉTODO PERSONALIZADO DEL MODELO: softDelete()
        $resultado = $carreraModel->softDelete($id); 

        if ($resultado) {
            return redirect()->to(base_url('carreras'))->with('mensaje', '🗑️ Carrera desactivada (eliminada lógicamente).');
        } else {
            return redirect()->to(base_url('carreras'))->with('error', '❌ Error al eliminar la carrera. Intente de nuevo.');
        }
    }
} 