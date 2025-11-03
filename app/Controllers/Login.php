<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel; 

class Login extends BaseController
{
    /**
     * Muestra la vista del formulario de login.
     */
    public function index()
    {
        // Si el usuario ya está logueado, lo redirigimos al dashboard
        if (session()->get('isLoggedIn')) {
            // Se redirige al dashboard del rol actual
            $rol_path = (session()->get('rol') === 'administrador') ? 'admin/dashboard' : session()->get('rol') . 'es';
            return redirect()->to(base_url($rol_path))->with('info', 'Ya estás autenticado.');
        }

        $data['title'] = 'Iniciar Sesión';
        // Asumiendo que la vista está en app/Views/login.php
        return view('login', $data); 
    }

    /**
     * Procesa la solicitud POST para autenticar al usuario.
     */
    public function auth()
    {
        $session = session();
        $model = new UsuarioModel(); 

        // ----------------------------------------------------------------
        // CORRECCIÓN CLAVE: Recibir el campo 'nombre_de_usuario' del formulario
        // ----------------------------------------------------------------
        $login_identifier = $this->request->getPost('nombre_de_usuario'); 
        $password_input = $this->request->getPost('password'); 

        // 1. Validación simple de campos
        if (empty($login_identifier) || empty($password_input)) {
            $session->setFlashdata('error', 'El identificador y la contraseña son requeridos.');
            return redirect()->back()->withInput();
        }
        
        // 2. Buscar usuario por el identificador
        $user = $model->where('nombre_de_usuario', $login_identifier)->first(); 
        
        if ($user) {
            // 3. Verificar Contraseña (La columna en la BD se llama 'contrasena')
            if (password_verify($password_input, $user['contrasena'])) {
                
                // 4. Iniciar Sesión exitosa
                $ses_data = [
                    'id_usuario' => $user['id_usuario'],
                    'username' => $user['nombre_de_usuario'], // Usar el nombre de usuario de la BD
                    'rol' => $user['rol'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                // 5. Redirigir según el rol
                if ($user['rol'] === 'administrador') {
                    return redirect()->to(base_url('admin/dashboard'))->with('success', 'Bienvenido Administrador.');
                } else {
                    // Por ejemplo: /profesores o /estudiantes
                    return redirect()->to(base_url($user['rol'] . 'es'))->with('success', 'Bienvenido ' . $user['rol'] . '.');
                }

            } else {
                // Contraseña incorrecta
                $session->setFlashdata('error', 'Contraseña incorrecta. Intente de nuevo.');
                return redirect()->back()->withInput();
            }
        } else {
            // Usuario no encontrado
            $session->setFlashdata('error', 'Identificador (Nombre de Usuario/DNI) no registrado.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Has cerrado sesión correctamente.');
    }
}
 