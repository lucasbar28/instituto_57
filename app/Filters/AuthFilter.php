<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filtro de Autenticación
 * * Este filtro verifica si un usuario ha iniciado sesión. Si el usuario 
 * NO ha iniciado sesión, lo redirige a la página de login.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Lógica ejecutada ANTES de que se ejecute el controlador.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Obtener el servicio de sesión
        $session = session();

        // 2. Verificar si el usuario NO está autenticado
        // Se utiliza 'isLoggedIn' para ser consistente con el controlador Login
        if (!$session->get('isLoggedIn')) { 
            // Si no está autenticado, redirigir a la página de login
            $session->setFlashdata('error', 'Debes iniciar sesión para acceder a esta área.');
            return redirect()->to(base_url('login'));
        }
    }

    /**
     * Lógica ejecutada DESPUÉS de que se ejecuta el controlador.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere lógica 'after' para el filtro de autenticación.
    }
}
 