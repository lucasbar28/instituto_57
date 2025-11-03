<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filtro personalizado para verificar la autenticación y los permisos de rol.
 * Si el usuario no está logueado, lo redirige al login.
 * Si está logueado, verifica si tiene permiso para acceder a la ruta.
 */
class RoleFilter implements FilterInterface
{
    /**
     * Lógica de pre-ejecución (antes de que se ejecute el controlador)
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // 1. Verificar Autenticación
        // Si el usuario no ha iniciado sesión, redirigir al login.
        if (! $session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Debe iniciar sesión para acceder a esta página.');
            return redirect()->to(base_url('login'));
        }

        // 2. Obtener el Rol del usuario (debe estar en la sesión)
        $userRole = $session->get('rol');

        // 3. Permiso Total para Administrador
        // Si es administrador, permite el acceso a todas las rutas protegidas.
        if ($userRole === 'administrador') {
            return; // Permite el acceso
        }

        // 4. Determinar la Ruta Actual y el método (GET, POST, etc.)
        $currentUrl = uri_string(); // Obtiene la URI actual (ej: 'carreras/editar/5')
        $requestMethod = $request->getMethod(); // Obtiene el método HTTP (ej: 'get', 'post')
        
        // Definir las rutas de SOLO LECTURA permitidas para profesores y alumnos
        // Estas son las rutas que SIEMPRE deben ser accesibles (GET)
        $accessibleRoutes = [
            'alumnos',
            'profesores',
            'carreras',
            'categorias',
            'cursos',
            'calendario',
            'campus',
        ];

        // 5. Aplicar Restricción de SÓLO LECTURA (GET) para Alumnos y Profesores
        
        // Buscamos si la URL actual comienza con alguna de las rutas accesibles
        $isAccessibleRoute = false;
        foreach ($accessibleRoutes as $route) {
            // Ejemplo: 'carreras' está en 'carreras/editar/5' o 'carreras'
            if (str_starts_with($currentUrl, $route)) {
                $isAccessibleRoute = true;
                break;
            }
        }
        
        // Si es una ruta accesible para lectura...
        if ($isAccessibleRoute) {
            
            // --- Regla del Requerimiento: Profesor y Alumno solo ven listados (GET) ---
            if ($userRole === 'profesor' || $userRole === 'alumno') {
                
                // Si el método NO es GET (es POST, PUT, DELETE, etc. que implica C/U/D), lo bloqueamos.
                if ($requestMethod !== 'get') {
                    $session->setFlashdata('error', 'Acceso denegado. Usted no tiene permisos para crear, editar o eliminar.');
                    return redirect()->back();
                }
                
                // Si es GET, verificamos que el rol tenga permiso de lectura para esa ruta específica.
                $hasReadPermission = false;
                
                if ($userRole === 'alumno') {
                    // Rutas permitidas para el ALUMNO (solo lectura)
                    $alumnoRoutes = ['alumnos', 'carreras', 'cursos', 'calendario', 'campus'];
                    if (in_array($route, $alumnoRoutes)) {
                        $hasReadPermission = true;
                    }
                }

                if ($userRole === 'profesor') {
                    // Rutas permitidas para el PROFESOR (solo lectura)
                    $profesorRoutes = ['profesores', 'carreras', 'categorias', 'cursos', 'calendario', 'campus'];
                    if (in_array($route, $profesorRoutes)) {
                        $hasReadPermission = true;
                    }
                }
                
                if ($hasReadPermission) {
                    return; // Permite el acceso de solo lectura
                }
            }
        }
        
        // Si llegamos aquí, la ruta no está permitida para este rol
        $session->setFlashdata('error', 'Acceso denegado. No tiene permisos suficientes para ver o gestionar esta sección.');
        return redirect()->to(base_url('dashboard')); // Redirigir a su dashboard por defecto o al inicio.
    }

    /**
     * Lógica de post-ejecución (después de que se ejecute el controlador)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hay lógica post-ejecución necesaria para la autenticación
    }
}
