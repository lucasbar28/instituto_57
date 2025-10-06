<?php

namespace App\Filters;

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
     * * @param RequestInterface $request
     * @param array|null       $arguments
     * @return ResponseInterface|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Obtener el servicio de sesión
        $session = session();

        // 2. Verificar si el usuario NO está autenticado
        // Asume que la variable de sesión 'logged_in' se establece a 'true' al iniciar sesión
        if (!$session->get('logged_in')) {
            // Si no está autenticado, redirigir a la página de login
            // y guardar un mensaje para que el usuario sepa por qué fue redirigido
            $session->setFlashdata('error', 'Debes iniciar sesión para acceder a esta área.');
            return redirect()->to(base_url('login'));
        }
    }

    /**
     * Lógica ejecutada DESPUÉS de que se ejecuta el controlador.
     * No necesitamos hacer nada aquí para la autenticación básica.
     * * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere lógica 'after' para el filtro de autenticación.
    }
}
 