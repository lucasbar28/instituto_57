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
        // Si el usuario ya está logueado, lo redirigimos
        if (session()->get('isLoggedIn')) {

            // 1. VERIFICACIÓN CRUCIAL DE CAMBIO OBLIGATORIO
            // Si el flag está en la sesión, forzamos el cambio de contraseña
            if (session()->get('cambio_obligatorio') == 1) {
                return redirect()->to(base_url('perfil/cambio-contrasena'))
                                 ->with('warning', 'Debe cambiar su contraseña antes de continuar.');
            }
            
            // 2. Lógica de redirección normal si no hay cambio pendiente
            $rol = session()->get('rol');
            $redirect_url = '/'; 
            
            switch ($rol) {
                case 'administrador':
                    $redirect_url = 'admin/dashboard';
                    break;
                case 'profesor':
                    $redirect_url = 'profesores'; 
                    break;
                case 'alumno':
                    $redirect_url = 'estudiantes'; 
                    break;
            }
            
            // Redirección final
            return redirect()->to(base_url($redirect_url))->with('info', 'Ya estás autenticado.');
        }

        $data['title'] = 'Iniciar Sesión';
        return view('login', $data); 
    }

    /**
     * Procesa la solicitud POST para autenticar al usuario.
     */
    public function auth()
    {
        $session = session();
        $model = new UsuarioModel(); 

        $login_identifier = $this->request->getPost('nombre_de_usuario'); 
        $password_input = $this->request->getPost('password'); 

        // 1. Validación simple de campos
        if (empty($login_identifier) || empty($password_input)) {
            $session->setFlashdata('error', 'El identificador y la contraseña son requeridos.');
            return redirect()->back()->withInput();
        }
        
        // 2. Buscar usuario por 'nombre_de_usuario'
        $user = $model->where('nombre_de_usuario', $login_identifier)->first(); 
        
        if ($user) {
            // 3. Verificar Contraseña
            if (password_verify($password_input, $user['contrasena'])) {
                
                // Aseguramos que el flag de la BD se guarda como entero
                $cambio_obligatorio = (int)$user['cambio_contrasena_obligatorio']; 
                
                // 4. Preparar y establecer datos de Sesión
                $ses_data = [
                    'id_usuario' => $user['id_usuario'],
                    'nombre_usuario' => $user['nombre_de_usuario'], 
                    'rol' => $user['rol'],
                    'cambio_obligatorio' => $cambio_obligatorio, 
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                // 5. VERIFICACIÓN DE CAMBIO DE CONTRASEÑA OBLIGATORIO
                if ($cambio_obligatorio == 1) {
                    // Redirigir a la ruta de cambio
                    return redirect()->to(base_url('perfil/cambio-contrasena'))
                                     ->with('warning', 'Por su seguridad, debe cambiar su contraseña temporal.');
                }
                
                // 6. Redirección normal según el rol (Solo si no hay cambio pendiente)
                switch ($user['rol']) {
                    case 'administrador':
                        return redirect()->to(base_url('admin/dashboard'));
                    case 'profesor':
                        return redirect()->to(base_url('profesores'));
                    case 'alumno':
                        return redirect()->to(base_url('estudiantes'));
                    default:
                        return redirect()->to(base_url('/'));
                }

            } else {
                // Contraseña incorrecta
                $session->setFlashdata('error', 'Credenciales no válidas. Intente de nuevo.');
                return redirect()->back()->withInput();
            }
        } else {
            // Usuario no encontrado
            $session->setFlashdata('error', 'Credenciales no válidas. Intente de nuevo.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Cierra la sesión del usuario.
     * Elimina todos los datos de la sesión y redirige al login.
     */
    public function logout()
    {
        // Limpia toda la sesión. Esto es seguro y efectivo.
        session()->destroy(); 
        
        // Redirige al login con un mensaje de éxito
        return redirect()->to(base_url('login'))->with('success', 'Has cerrado sesión correctamente.');
    }
}
 