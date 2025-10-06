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
        // Se valida el email como único tanto para el profesor como para el usuario
        if (!$this->validate([
            'nombre_completo' => 'required|min_length[3]|max_length[255]',
            'especialidad'    => 'required|min_length[3]|max_length[150]',
            // El email debe ser único en la tabla profesores y en la tabla usuarios (como nombre_de_usuario)
            'email'           => 'required|valid_email|is_unique[profesores.email]|is_unique[usuarios.nombre_de_usuario]',
            // Se asume que la contraseña inicial es el DNI o similar, y que se envía el campo 'dni_o_similar' desde el formulario
            'dni_o_similar'   => 'required|min_length[4]', 
            'telefono'        => 'permit_empty|max_length[20]',
        ],
        [
            'email' => [
                'is_unique' => 'Este email ya está en uso como credencial o en el registro de otro profesor.'
            ]
        ])) {
            // Si falla la validación, regresa al formulario con errores y datos
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- 2. PREPARAR DATOS Y USAR TRANSACCIÓN ---
        
        // Datos para la tabla 'usuarios'
        $usuarioData = [
            'nombre_de_usuario' => $datos['email'], // Usamos el email como nombre de usuario por simplicidad
            // Hashing de la contraseña (DNI)
            'contrasena'        => password_hash($datos['dni_o_similar'], PASSWORD_DEFAULT),
            'rol'               => 'profesor',
            'estado'            => 'activo'
        ];
        
        // Datos para la tabla 'profesores'
        $profesorData = [
            'nombre_completo' => $datos['nombre_completo'],
            'especialidad'    => $datos['especialidad'],
            'email'           => $datos['email'],
            'telefono'        => $datos['telefono'],
            // 'id_usuario' se añade después de la inserción de usuario
        ];

        $db->transStart(); // Inicia la transacción

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
            
            $db->transComplete(); // Finaliza la transacción
            
            if ($db->transStatus() === FALSE) {
                 // Si la transacción no fue exitosa, lanzar una excepción
                 throw new DatabaseException("La transacción de guardado falló.");
            }
            
            // Redirección exitosa
            return redirect()->to(base_url('profesores'))->with('mensaje', '✅ Profesor y usuario creados con éxito. Credencial: ' . $datos['email']);

        } catch (DatabaseException $e) {
            $db->transRollback(); // Revertir si algo falla
            log_message('error', 'Error al guardar profesor: ' . $e->getMessage());
            
            // Redirección con error
            return redirect()->back()->withInput()->with('error', '❌ Error al registrar el profesor o el usuario: ' . $e->getMessage());
        }
    }
}
 