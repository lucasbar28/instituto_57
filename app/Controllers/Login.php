<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class Login extends BaseController
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function index()
    {
        helper(['form']);
        $data['title'] = 'Iniciar Sesión'; // Título para el head
        echo view('login', $data);
    }

    /**
     * Procesa la autenticación del usuario.
     */
    public function auth()
    {
        $session = session();
        $model = new UsuarioModel();
        
        $email = $this->request->getVar('nombre_de_usuario');
        $password = $this->request->getVar('contrasena');
        
        // 1. Buscar el usuario por email (nombre_de_usuario)
        $usuario = $model->where('nombre_de_usuario', $email)->first();
        
        // --- DEPURACIÓN: Paso 1 ---
        // Si no encuentra el usuario, muestra un mensaje y el dump.
        if (is_null($usuario)) {
            $session->setFlashdata('msg', 'Por favor, ingrese un nombre de usuario y contraseña válidos.');
            
            // Si $usuario es null, devolvemos un dump de error.
            echo "<h2>--- ERROR DE DEPURACIÓN (USUARIO NO ENCONTRADO) ---</h2>";
            echo "<p>El modelo no pudo encontrar un registro para el email: <strong>$email</strong></p>";
            var_dump($usuario); 
            echo "<p>Vuelve al formulario con el mensaje de error.</p>";
            
            return redirect()->to(base_url('/login'));
        }
        
        // --- DEPURACIÓN: Paso 2 ---
        // Si encuentra el usuario, verifica la contraseña.
        $verificarContrasena = password_verify($password, $usuario['contrasena']);
        
        // Si la verificación de contraseña falla.
        if (! $verificarContrasena) {
            $session->setFlashdata('msg', 'Por favor, ingrese un nombre de usuario y contraseña válidos.');

            echo "<h2>--- ERROR DE DEPURACIÓN (CONTRASEÑA NO VÁLIDA) ---</h2>";
            echo "<p>Contraseña ingresada (texto plano): <strong>$password</strong></p>";
            echo "<p>Contraseña hasheada en BD: <strong>{$usuario['contrasena']}</strong></p>";
            echo "<p>Resultado de password_verify(): "; var_dump($verificarContrasena); echo "</p>";
            echo "<p>Vuelve al formulario con el mensaje de error.</p>";
            
            return redirect()->to(base_url('/login'));
        }

        // --- AUTENTICACIÓN EXITOSA ---
        // Si el usuario existe y la contraseña es correcta, crea la sesión
        $sesionData = [
            'id_usuario'  => $usuario['id_usuario'],
            'username'    => $usuario['nombre_de_usuario'],
            'rol'         => $usuario['rol'],
            'isLoggedIn'  => TRUE
        ];
        
        $session->set($sesionData);
        return redirect()->to(base_url('/')); // Redirigir a la página de inicio
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('/login'));
    }
} 