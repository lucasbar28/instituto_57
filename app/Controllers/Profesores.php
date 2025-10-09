<?php

namespace App\Controllers;

use App\Models\ProfesorModel;
use App\Models\UsuarioModel;
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

        // Muestra la vista 'profesores' (lista)
        return view('profesores', $data);
    }

    /**
     * Muestra el formulario para crear un nuevo profesor.
     */
    public function crear()
    {
        $data = [
            'validation' => \Config\Services::validation(), // Para mostrar errores
            'title'      => 'Registrar Nuevo Profesor',
        ];
        // Muestra la vista del formulario
        return view('profesores_form', $data);
    }

    /**
     * Procesa el formulario, guarda el nuevo usuario (credenciales) y el profesor (datos personales).
     */
    public function guardar()
    {
        $profesorModel = new ProfesorModel();
        $usuarioModel = new UsuarioModel();
        $db = \Config\Database::connect(); // Conexión para transacciones
        
        $datos = $this->request->getPost();

        // --- 1. REGLAS DE VALIDACIÓN ---
        if (!$this->validate([
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            'especialidad'    => 'required|min_length[3]|max_length[150]',
            'email'           => 'required|valid_email|is_unique[profesores.email]|is_unique[usuarios.nombre_de_usuario]',
            'dni_o_similar'   => 'required|min_length[4]', // Contraseña inicial
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
            'telefono'        => $datos['telefono'],
        ];

        $db->transStart();

        try {
            // A. Guardar el usuario y obtener el ID
            $id_usuario = $usuarioModel->insert($usuarioData);
            
            if (!$id_usuario) {
                 throw new DatabaseException("No se pudo insertar el registro del usuario.");
            }

            // B. Asignar el ID de usuario al profesor
            $profesorData['id_usuario'] = $id_usuario;
            
            // C. Guardar el registro del profesor
            $profesorModel->insert($profesorData);
            
            $db->transComplete();
            
            if ($db->transStatus() === FALSE) {
                 throw new DatabaseException("La transacción de guardado falló.");
            }
            
            return redirect()->to(base_url('profesores'))->with('mensaje', '✅ Profesor y usuario creados con éxito. Credencial: ' . $datos['email']);

        } catch (DatabaseException $e) {
            $db->transRollback();
            log_message('error', 'Error al guardar profesor: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', '❌ Error al registrar el profesor o el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario para editar un profesor existente.
     * @param int $id ID del profesor a editar.
     */
    public function editar($id)
    {
        $profesorModel = new ProfesorModel();
        
        $profesor = $profesorModel->find($id);
        
        if (empty($profesor)) {
            return redirect()->to(base_url('profesores'))->with('error', '❌ Profesor no encontrado.');
        }

        $data = [
            'profesor'   => $profesor, // Pasa el objeto profesor a la vista
            'validation' => \Config\Services::validation(),
            'title'      => 'Editar Profesor',
        ];

        return view('profesores_form', $data);
    }
    
    /**
     * Procesa el formulario de edición y actualiza el registro del profesor.
     * La actualización de credenciales (usuario) queda fuera de este flujo por seguridad,
     * se asume que se gestiona en un módulo de usuarios separado.
     */
    public function actualizar()
    {
        $profesorModel = new ProfesorModel();
        $datos = $this->request->getPost();
        
        $id_profesor = $datos['id_profesor'];

        // Obtiene el registro actual para chequear el ID de usuario
        $profesor_actual = $profesorModel->find($id_profesor);
        if (!$profesor_actual) {
             return redirect()->to(base_url('profesores'))->with('error', '❌ Error al actualizar: Profesor no encontrado.');
        }
        $id_usuario = $profesor_actual['id_usuario'];

        // --- REGLAS DE VALIDACIÓN para Actualizar ---
        // 1. Validar unicidad del email, EXCLUYENDO el registro actual del profesor
        // 2. Validar unicidad del email, EXCLUYENDO el registro actual del usuario
        if (!$this->validate([
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            'especialidad'    => 'required|min_length[3]|max_length[150]',
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

        // Solo se actualiza el email/nombre_de_usuario en la tabla 'usuarios' si el email cambió
        $usuarioData = [
            'nombre_de_usuario' => $datos['email'], 
        ];

        $usuarioModel = new UsuarioModel();
        $db = \Config\Database::connect();
        
        $db->transStart();
        
        try {
            // A. Actualizar el registro del profesor
            $profesorModel->update($id_profesor, $profesorData);

            // B. Actualizar el nombre de usuario (email) en la tabla 'usuarios'
            $usuarioModel->update($id_usuario, $usuarioData);

            $db->transComplete();
            
            if ($db->transStatus() === FALSE) {
                 throw new DatabaseException("La transacción de actualización falló.");
            }
            
            return redirect()->to(base_url('profesores'))->with('mensaje', '✅ Profesor actualizado con éxito!');

        } catch (DatabaseException $e) {
            $db->transRollback();
            log_message('error', 'Error al actualizar profesor: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', '❌ Error al actualizar el profesor y su credencial: ' . $e->getMessage());
        }
    }

    /**
     * Elimina el registro del profesor y su usuario asociado.
     * Esta operación DEBE ser transaccional.
     * @param int $id ID del profesor a eliminar.
     */
    public function eliminar($id)
    {
        $profesorModel = new ProfesorModel();
        $usuarioModel = new UsuarioModel();
        $db = \Config\Database::connect();
        
        // 1. Obtener el registro para conseguir el id_usuario
        $profesor = $profesorModel->find($id);

        if (!$profesor) {
            return redirect()->to(base_url('profesores'))->with('error', '❌ Profesor no encontrado.');
        }
        
        $id_usuario = $profesor['id_usuario'];

        $db->transStart(); // Inicia la transacción

        try {
            // A. Eliminar el profesor (Registro hijo)
            $profesorModel->delete($id);

            // B. Eliminar el usuario (Registro padre / credencial)
            $usuarioModel->delete($id_usuario);
            
            $db->transComplete(); // Finaliza la transacción

            if ($db->transStatus() === FALSE) {
                 throw new DatabaseException("La transacción de eliminación falló.");
            }
            
            return redirect()->to(base_url('profesores'))->with('mensaje', '🗑️ Profesor y usuario eliminados con éxito!');

        } catch (DatabaseException $e) {
            $db->transRollback();
            log_message('error', 'Error al eliminar profesor: ' . $e->getMessage());
            // Mensaje específico si hay cursos asociados (dependencia)
            if (strpos($e->getMessage(), '1451') !== false) { // 1451 es el error de llave foránea en MySQL
                return redirect()->to(base_url('profesores'))->with('error', '❌ Error: No se puede eliminar el profesor porque tiene cursos asignados. Desasigne los cursos primero.');
            }
            return redirect()->to(base_url('profesores'))->with('error', '❌ Error al eliminar el profesor y su credencial: ' . $e->getMessage());
        }
    }
}
 