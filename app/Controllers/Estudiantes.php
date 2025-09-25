<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\EstudianteModel;
use App\Models\CarreraModel;
use App\Models\UsuarioModel;

class Estudiantes extends Controller
{
    public function index()
    {
        helper('url');
        $model = new EstudianteModel();
        $data['estudiantes'] = $model->findAll();
        return view('estudiantes', $data);
    }

    public function crear()
    {
        helper('url');
        $carreraModel = new CarreraModel();
        $data['carreras'] = $carreraModel->findAll();
        return view('estudiantes_form', $data);
    }

    public function guardar()
    {
        $estudianteModel = new EstudianteModel();
        $usuarioModel = new UsuarioModel();

        // Datos para la tabla 'usuarios'
        $usuarioData = [
            'nombre_de_usuario' => $this->request->getPost('email'),
            'contrasena'        => password_hash($this->request->getPost('dni_matricula'), PASSWORD_DEFAULT),
            'rol'               => 'alumno',
            'estado'            => 'activo'
        ];

        // 1. Guardar el usuario y obtener su ID
        $id_usuario = $usuarioModel->insert($usuarioData);
        
        // 2. Preparar los datos para la tabla 'alumnos'
        $estudianteData = [
            'nombre_completo' => $this->request->getPost('nombre_completo'),
            'dni_matricula'   => $this->request->getPost('dni_matricula'),
            'email'           => $this->request->getPost('email'),
            'telefono'        => $this->request->getPost('telefono'),
            'id_carrera'      => $this->request->getPost('id_carrera'),
            'id_usuario'      => $id_usuario // Enlazar el alumno con el ID del usuario
        ];
        
        // 3. Guardar el registro del estudiante
        $estudianteModel->save($estudianteData);
        
        // Redirigir a la página principal de estudiantes
        return redirect()->to(base_url('estudiantes'));
    }
} 