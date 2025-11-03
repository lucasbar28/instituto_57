<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Controllers\BaseController;

class Login extends BaseController
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function index()
    {
        helper(['form']); 
        $data['title'] = 'Iniciar Sesión'; 
        
        return view('login', $data);
    }

    /**
     * Procesa la autenticación del usuario.
     */
    public function auth()
    {
        $session = session();
        $model = new UsuarioModel();
        
        $email = $this->request->getVar('nombre_de_usuario', FILTER_SANITIZE_EMAIL);
        $password = $this->request->getVar('contrasena');
        
        if (empty($email) || empty($password)) {
            $session->setFlashdata('error', 'Por favor, ingrese su nombre de usuario (email) y contraseña.');
            return redirect()->to(base_url('/login'));
        }

        $usuario = $model->where('nombre_de_usuario', $email)->first();
        
        if (is_null($usuario)) {
            $session->setFlashdata('error', 'Credenciales no válidas. Por favor, intente de nuevo.');
            return redirect()->to(base_url('/login'));
        }
        
        $verificarContrasena = password_verify($password, $usuario['contrasena']);
        
        if (! $verificarContrasena) {
            $session->setFlashdata('error', 'Credenciales no válidas. Por favor, intente de nuevo.');
            log_message('warning', 'Intento de login fallido para usuario: ' . $email);
            return redirect()->to(base_url('/login'));
        }
        
        // --- CORRECCIÓN DE SEGURIDAD: VERIFICAR ESTADO ---
        if ($usuario['estado'] !== 'activo') {
             $session->setFlashdata('error', 'Su cuenta está inactiva. Contacte al administrador.');
             return redirect()->to(base_url('/login'));
        }

        // --- AUTENTICACIÓN EXITOSA ---
        $sesionData = [
            'id_usuario'  => $usuario['id_usuario'], 
            'username'    => $usuario['nombre_de_usuario'],
            'rol'         => $usuario['rol'],
            'isLoggedIn'  => TRUE
        ];
        
        $session->set($sesionData);
        
        return redirect()->to(base_url('/')); 
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('/login'))->with('mensaje', '🚪 Sesión cerrada con éxito.');
    }
}