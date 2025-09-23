<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\EstudianteModel;
use App\Models\CarreraModel;

class Estudiantes extends Controller
{
    public function index()
    {
        $model = new EstudianteModel();
        $data['estudiantes'] = $model->findAll();
        return view('estudiantes', $data);
    }

    public function crear()
    {
        // Instancia el modelo de Carrera para obtener las opciones para el select
        $carreraModel = new CarreraModel();
        $data['carreras'] = $carreraModel->findAll();

        return view('estudiantes_form', $data);
    }

    public function guardar()
    {
        $estudianteModel = new EstudianteModel();
        
        // Obtiene los datos del formulario
        $data = [
            'nombre_completo' => $this->request->getPost('nombre_completo'),
            'dni_matricula'   => $this->request->getPost('dni_matricula'),
            'email'           => $this->request->getPost('email'),
            'telefono'        => $this->request->getPost('telefono'),
            'id_carrera'      => $this->request->getPost('id_carrera')
        ];
        
        // Guarda el registro en la base de datos
        $estudianteModel->save($data);
        
        // Redirige a la página principal de estudiantes
        return redirect()->to(base_url('estudiantes'));
    }
} 