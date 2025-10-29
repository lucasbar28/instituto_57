<?php

namespace App\Controllers;

// Importar los modelos que vamos a necesitar
use App\Models\CarreraModel;
use App\Models\CategoriaModel;

class Carreras extends BaseController
{
    /**
     * Muestra la lista de todas las carreras, filtrando solo las activas (estado = 1).
     */
    public function index()
    {
        $carreraModel = new CarreraModel();
        
        $data = [
            // USANDO EL MÉTODO PERSONALIZADO DEL MODELO: findAllActive()
            'carreras' => $carreraModel->findAllActive(), 
            'page_title' => 'Lista de Carreras'
        ];
        
        return view('carreras_list', $data); 
    }
    
    /**
     * Prepara y muestra el formulario para crear una nueva carrera.
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

        // --- Reglas de Validación ---
        // Nota: Las validaciones de 'estado' (0 o 1) se manejan dentro del modelo.
        if (! $this->validate([
            'nombre_carrera' => 'required|min_length[3]|is_unique[carreras.nombre_carrera]', 
            'duracion'       => 'required|integer|greater_than[0]',
            'modalidad'      => 'required',
            'id_categoria'   => 'required|integer',
        ], 
        [
            'nombre_carrera' => [
                'is_unique' => 'Ya existe una carrera con este nombre. Por favor, ingrese un nombre diferente.'
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- Inserción de Datos ---
        // El estado se establece en 1 (Activa) por defecto gracias al callback 
        // 'setDefaultEstado' que definimos en el CarreraModel.
        $carreraModel->insert([
            'nombre_carrera' => $datos['nombre_carrera'],
            'duracion'       => $datos['duracion'],
            'modalidad'      => $datos['modalidad'],
            'id_categoria'   => $datos['id_categoria'],
            // Se elimina 'estado' => 1, ya que el modelo lo hace por nosotros
        ]);

        return redirect()->to(base_url('carreras'))->with('mensaje', '✅ ¡Carrera registrada con éxito!');
    }

    /**
     * Prepara y muestra el formulario para editar una carrera existente.
     * @param int $id ID de la carrera a editar.
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
        
        $id = $datos['id_carrera'];

        // --- Reglas de Validación para Actualización ---
        // Excluimos la carrera actual del chequeo 'is_unique'
        if (! $this->validate([
            'nombre_carrera' => "required|min_length[3]|is_unique[carreras.nombre_carrera,id_carrera,{$id}]", 
            'duracion'       => 'required|integer|greater_than[0]',
            'modalidad'      => 'required',
            'id_categoria'   => 'required|integer',
            // El estado debe aceptar 0 o 1, y no es obligatorio para la validación
            'estado'         => 'permit_empty|integer|in_list[0,1]', 
        ],
        [
            'nombre_carrera' => [
                'is_unique' => 'Ya existe otra carrera con este nombre. Por favor, ingrese un nombre diferente.'
            ]
        ])) {
            // Si hay error, volvemos al formulario con los datos y errores
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
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

        // --- Actualización de Datos ---
        $carreraModel->update($id, $data_to_update);

        return redirect()->to(base_url('carreras'))->with('mensaje', '✅ ¡Carrera actualizada con éxito!');
    }

    /**
     * Realiza la eliminación lógica (cambio de estado a 0) de una carrera.
     * @param int $id ID de la carrera a eliminar.
     */
    public function eliminar($id)
    {
        $carreraModel = new CarreraModel();

        $carrera = $carreraModel->find($id);

        if (!$carrera) {
            return redirect()->to(base_url('carreras'))->with('error', '❌ Carrera no encontrada.');
        }

        // USANDO EL MÉTODO PERSONALIZADO DEL MODELO: softDelete($id)
        $carreraModel->softDelete($id); 

        return redirect()->to(base_url('carreras'))->with('mensaje', '🗑️ Carrera eliminada (desactivada) con éxito.');
    }
}
