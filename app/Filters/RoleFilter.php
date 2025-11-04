<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filtro de Roles
 * * Verifica si el usuario autenticado tiene un rol permitido para acceder
 * a la ruta y aplica la lógica de cambio de contraseña obligatorio.
 */
class RoleFilter implements FilterInterface
{
    /**
     * Lógica ejecutada ANTES de que se ejecute el controlador.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // 1. Verificación de Autenticación 
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Debes iniciar sesión para acceder.');
            return redirect()->to(base_url('login'));
        }

        // --- EXCLUSIÓN DE RUTA CRÍTICA (CORRECCIÓN: Usar getUri()) ---
        // Obtenemos la URI actual para verificar si estamos en la página de cambio de contraseña
        try {
            // Forma correcta de acceder a la URI en CodeIgniter 4
            $currentUri = $request->getUri()->getPath();
        } catch (\Exception $e) {
            // Manejo de errores por si la request no tiene URI (poco probable en filtros)
            log_message('error', 'Error al obtener URI en RoleFilter: ' . $e->getMessage());
            $currentUri = ''; 
        }

        // La comparación debe ser flexible para incluir la ruta de cambio de contraseña.
        // Asume que la ruta es 'perfil/cambio-contrasena'
        $isChangePasswordPage = strpos($currentUri, 'perfil/cambio-contrasena') !== false;
        
        // ----------------------------------------------------------------------
        // 2. Verificación de Cambio de Contraseña Obligatorio
        // ----------------------------------------------------------------------
        
        // Si el cambio de contraseña es obligatorio Y NO estamos en la página de cambio:
        if ($session->get('cambio_obligatorio') == 1 && !$isChangePasswordPage) {
            
            // Redirección forzada. Esto evita el bucle infinito.
            return redirect()->to(base_url('perfil/cambio-contrasena'))
                             ->with('warning', 'Debe cambiar su contraseña antes de continuar. Acceso restringido.');
        }

        // ----------------------------------------------------------------------
        // 3. Verificación de Roles
        // ----------------------------------------------------------------------
        
        $user_role = $session->get('rol');
        
        // Si hay roles definidos en los argumentos Y el rol del usuario NO está en la lista de permitidos
        if (!empty($arguments) && !in_array($user_role, $arguments)) {
            
            $session->setFlashdata('error', 'Acceso denegado. Tu rol (' . $user_role . ') no está autorizado para esta área.');
            
            // Redirigir al dashboard específico
            switch ($user_role) {
                case 'administrador':
                    return redirect()->to(base_url('admin/dashboard'));
                case 'profesor':
                    return redirect()->to(base_url('profesores')); 
                case 'alumno':
                    return redirect()->to(base_url('estudiantes'));
                default:
                    return redirect()->to(base_url('/'));
            }
        }
    }

    /**
     * Lógica ejecutada DESPUÉS de que se ejecuta el controlador.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere ninguna acción después
    }
}
 