<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProfesorModel;
use App\Models\UsuarioModel;

class Profesores extends Controller
{
    public function index()
    {
        $model = new ProfesorModel();
        $data['profesores'] = $model->findAll();
        return view('profesores', $data);
    }

    public function crear()
    {
        return view('profesores_form');
    }

    public function guardar()
    {
        $profesorModel = new ProfesorModel();
        $usuarioModel = new UsuarioModel();

        // Datos para la tabla 'usuarios'
        $usuarioData = [
            'nombre_de_usuario' => $this->request->getPost('email'),
            'contrasena'        => password_hash($this->request->getPost('dni_o_similar'), PASSWORD_DEFAULT),
            'rol'               => 'profesor',
            'estado'            => 'activo'
        ];

        // 1. Guardar el usuario y obtener su ID
        $id_usuario = $usuarioModel->insert($usuarioData);
        
        // 2. Preparar los datos para la tabla 'profesores'
        $profesorData = [
            'nombre_completo' => $this->request->getPost('nombre_completo'),
            'especialidad'    => $this->request->getPost('especialidad'),
            'email'           => $this->request->getPost('email'),
            'telefono'        => $this->request->getPost('telefono'),
            'id_usuario'      => $id_usuario // Enlazar el profesor con el ID del usuario
        ];
        
        // 3. Guardar el registro del profesor
        $profesorModel->save($profesorData);
        
        // Redirigir a la página principal de profesores
        return redirect()->to(base_url('profesores'));
    }
} 