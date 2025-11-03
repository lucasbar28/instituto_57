<?php

namespace App\Controllers;

use App\Models\ProfesorModel;
use App\Models\UsuarioModel;
use App\Models\CursoModel; 
use App\Controllers\BaseController; 
use CodeIgniter\Database\Exceptions\DatabaseException; 

class Profesores extends BaseController
{
    /**
     * Muestra la lista de todos los profesores.
     */
    public function index()
    {
        $model = new ProfesorModel();
        
        $data = [
            'profesores' => $model->findAll(),
            'title'      => 'Lista de Profesores',
        ];

        // CAMBIO: Ahora carga la vista 'profesores' (que está en app/Views/profesores.php)
        return view('profesores', $data);
    }

    /**
     * Muestra el formulario para crear un nuevo profesor.
     */
    public function crear()
    {
        $data = [
            'validation' => \Config\Services::validation(), 
            'title'      => 'Registrar Nuevo Profesor',
        ];
        return view('profesores_form', $data);
    }

    /**
     * Procesa el formulario, guarda el nuevo usuario (credenciales) y el profesor (datos personales).
     */
    public function guardar()
    {
        $profesorModel = new ProfesorModel();
        $usuarioModel = new UsuarioModel();
        $db = \Config\Database::connect(); 
        
        $datos = $this->request->getPost();

        // --- 1. REGLAS DE VALIDACIÓN ---
        if (!$this->validate([
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            'especialidad'    => 'required|min_length[3]|max_length[150]',
            'email'           => 'required|valid_email|is_unique[profesores.email]|is_unique[usuarios.nombre_de_usuario]',
            'telefono'        => 'permit_empty|max_length[20]',
        ],
        [
            'email' => [
                'is_unique' => 'Este email ya está en uso como credencial o en el registro de otro profesor.'
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- 2. PREPARAR DATOS Y USAR TRANSACCIÓN ---
        
        // Generamos una contraseña temporal segura
        $contrasena_inicial = bin2hex(random_bytes(8)); 
        
        $usuarioData = [
            'nombre_de_usuario' => $datos['email'],
            'contrasena'        => password_hash($contrasena_inicial, PASSWORD_DEFAULT),
            'rol'               => 'profesor',
            'estado'            => 'activo'
        ];
        
        $profesorData = [
            'nombre_completo' => $datos['nombre_completo'],
            'especialidad'    => $datos['especialidad'],
            'email'           => $datos['email'],
            'telefono'        => $datos['telefono'],
        ];

        $db->transStart();

        try {
            $id_usuario = $usuarioModel->insert($usuarioData);
            
            if (!$id_usuario) {
                $error_detalle = $usuarioModel->errors() ? implode(', ', $usuarioModel->errors()) : "Error desconocido al insertar usuario.";
                throw new DatabaseException("No se pudo insertar el registro del usuario: " . $error_detalle);
            }

            $profesorData['id_usuario'] = $id_usuario;
            
            if (!$profesorModel->insert($profesorData)) {
                $error_detalle = $profesorModel->errors() ? implode(', ', $profesorModel->errors()) : "Error desconocido al insertar profesor.";
                throw new DatabaseException("No se pudo insertar el registro del profesor: " . $error_detalle);
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === FALSE) {
                throw new DatabaseException("La transacción de guardado falló. Estado de la BD: FALSE.");
            }
            
            // Informamos al admin la contraseña temporal generada
            return redirect()->to(base_url('profesores'))->with('mensaje', '✅ Profesor y usuario creados con éxito. Contraseña Temporal: ' . $contrasena_inicial);

        } catch (\Exception $e) {
            if ($db->transStatus() === TRUE) {
                $db->transRollback();
            }
            log_message('error', 'Error al guardar profesor: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', '❌ Error al registrar el profesor o el usuario: ' . $e->getMessage());
        }
    }
    
    /**
     * Muestra los detalles de un profesor específico. (Ruta: GET /profesores/show/ID)
     */
    public function ver($id = null)
    {
        $profesorModel = new ProfesorModel();

        if ($id === null) {
            return redirect()->to(base_url('profesores'))->with('error', 'ID de profesor no especificado.');
        }

        // Obtener el profesor
        $profesor = $profesorModel->find($id);

        if (empty($profesor)) {
            // Lanza una excepción 404 si el profesor no existe
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('No se encontró el profesor con ID: ' . $id);
        }

        // Además, deberíamos obtener el email de la tabla 'usuarios' usando el 'id_usuario'
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->find($profesor['id_usuario']);

        // Agregar el email del usuario para la vista de detalle
        if ($usuario) {
             $profesor['email'] = $usuario['nombre_de_usuario'];
        } else {
             $profesor['email'] = 'Usuario no encontrado';
        }


        $data = [
            'profesor' => $profesor,
            'title'    => 'Detalle del Profesor: ' . $profesor['nombre_completo']
        ];

        // Esta vista DEBE estar en app/Views/profesores/ver.php
        return view('profesores/ver', $data); 
    }

    /**
     * Muestra el formulario para editar un profesor existente.
     */
    public function editar($id)
    {
        $profesorModel = new ProfesorModel();
        
        $profesor = $profesorModel->find($id);
        
        if (empty($profesor)) {
            return redirect()->to(base_url('profesores'))->with('error', '❌ Profesor no encontrado.');
        }

        $data = [
            'profesor'   => $profesor, 
            'validation' => \Config\Services::validation(),
            'title'      => 'Editar Profesor',
        ];

        return view('profesores_form', $data);
    }
    
    /**
     * Procesa el formulario de edición y actualiza el registro del profesor.
     */
    public function actualizar()
    {
        $profesorModel = new ProfesorModel();
        $usuarioModel = new UsuarioModel();
        $db = \Config\Database::connect();
        
        $datos = $this->request->getPost();
        
        $id_profesor = $datos['id_profesor'];

        $profesor_actual = $profesorModel->find($id_profesor);
        if (!$profesor_actual) {
            return redirect()->to(base_url('profesores'))->with('error', '❌ Error al actualizar: Profesor no encontrado.');
        }
        $id_usuario = $profesor_actual['id_usuario'];

        // --- REGLAS DE VALIDACIÓN ---
        if (!$this->validate([
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            'especialidad'    => 'required|min_length[3]|max_length[150]',
            // CORRECCIÓN: Usa las PK correctas ('id_profesor' y 'id_usuario')
            'email'           => "required|valid_email|is_unique[profesores.email,id_profesor,{$id_profesor}]|is_unique[usuarios.nombre_de_usuario,id_usuario,{$id_usuario}]",
            'telefono'        => 'permit_empty|max_length[20]',
        ],
        [
            'email' => [
                'is_unique' => 'Este email ya está en uso como credencial o en el registro de otro profesor.'
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- DATOS DE ACTUALIZACIÓN ---
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
            
            if ($db->transStatus() === FALSE) {
                throw new DatabaseException("La transacción de actualización falló.");
            }
            
            return redirect()->to(base_url('profesores'))->with('mensaje', '✅ Profesor actualizado con éxito!');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al actualizar profesor: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', '❌ Error al actualizar el profesor y su credencial: ' . $e->getMessage());
        }
    }

    /**
     * Elimina el registro del profesor y su usuario asociado.
     */
    public function eliminar($id)
    {
        $profesorModel = new ProfesorModel();
        $usuarioModel = new UsuarioModel();
        $db = \Config\Database::connect();
        
        $profesor = $profesorModel->find($id);

        if (!$profesor) {
            return redirect()->to(base_url('profesores'))->with('error', '❌ Profesor no encontrado.');
        }
        
        $id_usuario = $profesor['id_usuario'];

        $db->transStart(); 

        try {
            // Eliminar el profesor (esto debería, a su vez, eliminar el usuario
            // si tienes configurado ON DELETE CASCADE en la FK de profesores.id_usuario)
            $profesorModel->delete($id);

            // Nota: Si la FK tiene ON DELETE CASCADE, la siguiente línea es redundante
            // $usuarioModel->delete($id_usuario); 
            
            $db->transComplete(); 

            if ($db->transStatus() === FALSE) {
                throw new DatabaseException("La transacción de eliminación falló.");
            }
            
            return redirect()->to(base_url('profesores'))->with('mensaje', '🗑️ Profesor y usuario eliminados con éxito!');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al eliminar profesor: ' . $e->getMessage());
            
            return redirect()->to(base_url('profesores'))->with('error', '❌ Error al eliminar el profesor y su credencial: ' . $e->getMessage());
        }
    }
}
 