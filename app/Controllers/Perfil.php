<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Perfil extends BaseController
{
    /**
     * Muestra el formulario de cambio de contraseña forzado.
     * El usuario solo llega aquí si el flag 'cambio_obligatorio' está activo en la sesión.
     */
    public function cambioContrasena()
    {
        $session = session();

        // Si no está logueado o el cambio no es obligatorio, redirigir
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión para cambiar su contraseña.');
        }

        // Permitimos ver la página si el usuario está forzado (cambio_obligatorio == 1)
        // o si simplemente quiere cambiarla voluntariamente (cambio_obligatorio == 0 o no definida)
        
        $data = [
            'title' => 'Cambio de Contraseña Obligatorio',
            'is_forced' => $session->get('cambio_obligatorio') == 1, // Para adaptar el mensaje de la vista
            'validation' => \Config\Services::validation(),
        ];

        return view('perfil/cambio_contrasena', $data);
    }

    /**
     * Procesa la solicitud POST para actualizar la contraseña del usuario.
     */
    public function actualizarContrasena()
    {
        $session = session();
        $usuarioModel = new UsuarioModel();

        if (!$session->get('isLoggedIn')) {
             return redirect()->to(base_url('login'))->with('error', 'Su sesión ha expirado.');
        }

        $id_usuario = $session->get('id_usuario');
        $is_forced = $session->get('cambio_obligatorio') == 1;

        // --- 1. Reglas de Validación de Contraseña ---
        if (!$this->validate([
            'contrasena' => 'required|min_length[8]',
            'confirmacion_contrasena' => 'required|matches[contrasena]',
        ],
        [
            'contrasena' => [
                'required' => 'La nueva contraseña es obligatoria.',
                'min_length' => 'La contraseña debe tener al menos 8 caracteres.',
            ],
            'confirmacion_contrasena' => [
                'required' => 'Debe confirmar la nueva contraseña.',
                'matches' => 'Las contraseñas no coinciden.',
            ]
        ])) {
            // Si falla la validación, volvemos a la vista de cambio
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- 2. Preparar Datos para la Actualización ---
        $nueva_contrasena = $this->request->getPost('contrasena');

        $updateData = [
            // El UsuarioModel se encargará de hashear la contraseña
            'contrasena' => $nueva_contrasena,
        ];
        
        // Si el cambio era obligatorio, debemos resetear el flag
        if ($is_forced) {
            $updateData['cambio_contrasena_obligatorio'] = 0;
        }

        // --- 3. Actualizar en la Base de Datos ---
        try {
            if ($usuarioModel->update($id_usuario, $updateData)) {
                
                // 4. Actualizar la Sesión (Solo si era obligatorio)
                if ($is_forced) {
                    $session->set('cambio_obligatorio', 0);
                    $mensaje = '✅ Contraseña actualizada con éxito. Ya puedes acceder a tu panel.';
                } else {
                    $mensaje = '✅ Contraseña actualizada con éxito.';
                }

                // 5. Redireccionar al dashboard según el rol
                $rol = $session->get('rol');
                $redirect_url = '/'; 
                if ($rol === 'administrador') {
                    $redirect_url = 'admin/dashboard';
                } elseif ($rol === 'profesor') {
                    $redirect_url = 'profesores'; 
                } elseif ($rol === 'alumno') {
                    $redirect_url = 'estudiantes';
                }
                
                return redirect()->to(base_url($redirect_url))->with('success', $mensaje);
            } else {
                return redirect()->back()->withInput()->with('error', '❌ Error desconocido al guardar la contraseña.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar contraseña: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', '❌ Error al actualizar la contraseña: ' . $e->getMessage());
        }
    }
}
 