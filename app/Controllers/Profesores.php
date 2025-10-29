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
     * NOTA: La contraseña inicial se genera de forma segura y aleatoria.
     */
    public function guardar()
    {
        $profesorModel = new ProfesorModel();
        $usuarioModel = new UsuarioModel();
        $db = \Config\Database::connect(); // Conexión para transacciones
        
        $datos = $this->request->getPost();

        // --- 1. REGLAS DE VALIDACIÓN (DNI_O_SIMILAR ELIMINADO) ---
        // Se valida contra ambas tablas para asegurar la unicidad del email.
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
        
        // **IMPORTANTE:** Generamos una contraseña temporal segura y aleatoria.
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
            // A. Guardar el usuario y obtener el ID
            $id_usuario = $usuarioModel->insert($usuarioData);
            
            // Si el modelo de usuario falló (ej: error en la BD o validación final)
            if (!$id_usuario) {
                // Si el modelo retorna errores de validación, usarlos, sino usar un error genérico
                $error_detalle = $usuarioModel->errors() ? implode(', ', $usuarioModel->errors()) : "Error desconocido al insertar usuario.";
                throw new DatabaseException("No se pudo insertar el registro del usuario: " . $error_detalle);
            }

            // B. Asignar el ID de usuario al profesor
            $profesorData['id_usuario'] = $id_usuario;
            
            // C. Guardar el registro del profesor
            if (!$profesorModel->insert($profesorData)) {
                $error_detalle = $profesorModel->errors() ? implode(', ', $profesorModel->errors()) : "Error desconocido al insertar profesor.";
                throw new DatabaseException("No se pudo insertar el registro del profesor: " . $error_detalle);
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === FALSE) {
                // Esto maneja errores de la BD que no fueron capturados por el 'insert()'
                throw new DatabaseException("La transacción de guardado falló. Estado de la BD: FALSE.");
            }
            
            // Mensaje de éxito con la contraseña inicial generada
            return redirect()->to(base_url('profesores'))->with('mensaje', '✅ Profesor y usuario creados con éxito. Credencial: ' . $datos['email'] . ' | Contraseña Temporal: ' . $contrasena_inicial);

        } catch (\Exception $e) {
            // Se revierte si el 'insert' falló o si el 'transStatus' es FALSE
            if ($db->transStatus() === TRUE) {
                $db->transRollback();
            }
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
        $usuarioModel = new UsuarioModel();
        $db = \Config\Database::connect();
        
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
            'email'           => "required|valid_email|is_unique[profesores.email,id,{$id_profesor}]|is_unique[usuarios.nombre_de_usuario,id,{$id_usuario}]",
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

        // Solo se actualiza el email/nombre_de_usuario en la tabla 'usuarios'
        $usuarioData = [
            'nombre_de_usuario' => $datos['email'], 
        ];

        
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

        } catch (\Exception $e) {
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

        } catch (\Exception $e) {
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
