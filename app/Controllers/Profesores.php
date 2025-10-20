<?php

namespace App\Controllers;

use App\Models\ProfesorModel;
use App\Models\UsuarioModel;
use App\Controllers\BaseController;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Services; // Importa los servicios correctamente

class Profesores extends BaseController
{
    public function index()
    {
        $model = new ProfesorModel();

        $data = [
            'profesores' => $model->findAll(),
            'title'      => 'Lista de Profesores',
        ];

        return view('profesores', $data);
    }

    public function crear()
    {
        $validation = Services::validation(); // Carga directa del validador

        $data = [
            'validation' => $validation,
            'title'      => 'Registrar Nuevo Profesor',
        ];

        return view('profesores_form', $data);
    }

    public function guardar()
    {
        helper(['form']); // por si no está cargado
    
        $profesorModel = new \App\Models\ProfesorModel();
        $usuarioModel  = new \App\Models\UsuarioModel();
        $db = \Config\Database::connect();
        $datos = $this->request->getPost();
    
        // Validación
        $rules = [
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            'especialidad'    => 'required|min_length[3]|max_length[150]',
            'email'           => 'required|valid_email|is_unique[profesores.email]|is_unique[usuarios.nombre_de_usuario]',
            'dni_o_similar'   => 'required|min_length[4]',
            'telefono'        => 'permit_empty|max_length[20]',
        ];
    
        if (!$this->validate($rules)) {
            // Muestra errores de validación en pantalla
            return view('profesores_form', [
                'validation' => $this->validator,
                'title' => 'Registrar Nuevo Profesor'
            ]);
        }
    
        // Datos a insertar
        $usuarioData = [
            'nombre_de_usuario' => $datos['email'],
            'contrasena'        => password_hash($datos['dni_o_similar'], PASSWORD_DEFAULT),
            'rol'               => 'profesor',
            'estado'            => 'activo'
        ];
    
        $profesorData = [
            'nombre_completo' => $datos['nombre_completo'],
            'especialidad'    => $datos['especialidad'],
            'email'           => $datos['email'],
            'telefono'        => $datos['telefono']
        ];
    
        try {
            $db->transStart();
    
            $id_usuario = $usuarioModel->insert($usuarioData);
            if (!$id_usuario) {
                throw new \Exception('Error al crear el usuario: ' . implode(', ', $usuarioModel->errors()));
            }
    
            $profesorData['id_usuario'] = $id_usuario;
            if (!$profesorModel->insert($profesorData)) {
                throw new \Exception('Error al crear el profesor: ' . implode(', ', $profesorModel->errors()));
            }
    
            $db->transComplete();
    
            if ($db->transStatus() === false) {
                throw new \Exception('Falló la transacción de base de datos.');
            }
    
            return redirect()
                ->to(base_url('profesores'))
                ->with('mensaje', '✅ Profesor y usuario creados con éxito.');
    
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Error al registrar profesor: ' . $e->getMessage());
    
            // Muestra error directamente (para depurar)
            return view('profesores_form', [
                'validation' => $this->validator,
                'title' => 'Registrar Nuevo Profesor',
                'error' => $e->getMessage()
            ]);
        }
    }
    

    

    public function editar($id)
    {
        $profesorModel = new ProfesorModel();
        $profesor = $profesorModel->find($id);

        if (empty($profesor)) {
            return redirect()->to(base_url('profesores'))->with('error', '❌ Profesor no encontrado.');
        }

        $data = [
            'profesor'   => $profesor,
            'validation' => Services::validation(),
            'title'      => 'Editar Profesor',
        ];

        return view('profesores_form', $data);
    }

    public function actualizar()
    {
        $profesorModel = new ProfesorModel();
        $usuarioModel  = new UsuarioModel();
        $db = \Config\Database::connect();
        $datos = $this->request->getPost();

        $id_profesor = $datos['id_profesor'];
        $profesor_actual = $profesorModel->find($id_profesor);

        if (!$profesor_actual) {
            return redirect()->to(base_url('profesores'))->with('error', '❌ Profesor no encontrado.');
        }

        $id_usuario = $profesor_actual['id_usuario'];

        $rules = [
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            'especialidad'    => 'required|min_length[3]|max_length[150]',
            'email'           => "required|valid_email|is_unique[profesores.email,id_profesor,{$id_profesor}]|is_unique[usuarios.nombre_de_usuario,id_usuario,{$id_usuario}]",
            'telefono'        => 'permit_empty|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', Services::validation()->getErrors());
        }

        $profesorData = [
            'nombre_completo' => $datos['nombre_completo'],
            'especialidad'    => $datos['especialidad'],
            'email'           => $datos['email'],
            'telefono'        => $datos['telefono'],
        ];

        $usuarioData = [
            'nombre_de_usuario' => $datos['email'],
        ];

        $db->transStart();

        try {
            $profesorModel->update($id_profesor, $profesorData);
            $usuarioModel->update($id_usuario, $usuarioData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new DatabaseException("La transacción de actualización falló.");
            }

            return redirect()->to(base_url('profesores'))->with('mensaje', '✅ Profesor actualizado con éxito.');

        } catch (DatabaseException $e) {
            $db->transRollback();
            log_message('error', 'Error al actualizar profesor: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', '❌ Error al actualizar: ' . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        $profesorModel = new ProfesorModel();
        $usuarioModel  = new UsuarioModel();
        $db = \Config\Database::connect();

        $profesor = $profesorModel->find($id);

        if (!$profesor) {
            return redirect()->to(base_url('profesores'))->with('error', '❌ Profesor no encontrado.');
        }

        $id_usuario = $profesor['id_usuario'];

        $db->transStart();

        try {
            $profesorModel->delete($id);
            $usuarioModel->delete($id_usuario);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new DatabaseException("La transacción de eliminación falló.");
            }

            return redirect()->to(base_url('profesores'))->with('mensaje', '🗑️ Profesor y usuario eliminados con éxito!');

        } catch (DatabaseException $e) {
            $db->transRollback();
            log_message('error', 'Error al eliminar profesor: ' . $e->getMessage());
            if (strpos($e->getMessage(), '1451') !== false) {
                return redirect()->to(base_url('profesores'))->with('error', '❌ No se puede eliminar el profesor porque tiene cursos asignados.');
            }
            return redirect()->to(base_url('profesores'))->with('error', '❌ Error: ' . $e->getMessage());
        }
    }
}
