<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use App\Controllers\BaseController; // Asegúrate de usar BaseController

class Login extends BaseController
{
    /**
     * Muestra la vista del formulario de login.
     */
    public function index()
    {
        // Si el usuario ya está autenticado, redirigir a la página principal
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('home'));
        }
        
        // Muestra la vista del formulario de login
        return view('login');
    }

    /**
     * Procesa la solicitud de inicio de sesión.
     */
    public function auth()
    {
        $session = session();
        $model = new UsuarioModel();
        $username = $this->request->getPost('nombre_de_usuario');
        $password = $this->request->getPost('contrasena');

        // 1. Validar campos
        if (!$this->validate([
            'nombre_de_usuario' => 'required',
            'contrasena' => 'required|min_length[4]' // Mínimo de 4 caracteres (ajusta si es necesario)
        ])) {
            return redirect()->back()->withInput()->with('error', 'Por favor, ingrese un nombre de usuario y contraseña válidos.');
        }

        // 2. Buscar usuario
        $user = $model->where('nombre_de_usuario', $username)->first();

        if ($user) {
            // 3. Verificar contraseña (asumiendo que las contraseñas están hasheadas con password_hash)
            if (password_verify($password, $user['contrasena'])) { 
                
                // 4. Establecer sesión exitosa
                $ses_data = [
                    'id_usuario'  => $user['id_usuario'],
                    'nombre'      => $user['nombre_de_usuario'],
                    'rol'         => $user['rol'], // Asumiendo que tienes un campo 'rol'
                    'isLoggedIn'  => TRUE
                ];
                $session->set($ses_data);

                // 5. Redireccionar al dashboard
                return redirect()->to(base_url('/')); 
            } else {
                // Contraseña incorrecta
                $session->setFlashdata('error', 'Contraseña incorrecta.');
                return redirect()->to(base_url('login'))->withInput();
            }
        } else {
            // Usuario no encontrado
            $session->setFlashdata('error', 'Nombre de usuario no encontrado.');
            return redirect()->to(base_url('login'))->withInput();
        }
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Has cerrado sesión exitosamente.');
    }
}
 