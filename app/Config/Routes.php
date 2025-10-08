<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Rutas de Login (DEBEN seguir activas)
$routes->get('login', 'Login::index');
$routes->post('login/auth', 'Login::auth');
$routes->get('logout', 'Login::logout');

// --------------------------------------------------------------------------
// RUTAS DE GESTIÓN (Acceso directo temporal)
// --------------------------------------------------------------------------

// Rutas de Profesores
$routes->get('profesores', 'Profesores::index');
$routes->post('profesores/crear', 'Profesores::crear');
$routes->post('profesores/guardar', 'Profesores::guardar');
// ... (Añadir rutas de editar/eliminar si existen)

// Rutas de Carreras
$routes->get('carreras', 'Carreras::index');
$routes->post('carreras/crear', 'Carreras::crear');
$routes->post('carreras/guardar', 'Carreras::guardar');
// ...

// Rutas de Categorías
$routes->get('categorias/crear', 'Categorias::crear');
$routes->post('categorias/guardar', 'Categorias::guardar');
// ...

// Rutas de Cursos
// ¡CORRECCIÓN AQUÍ! Ahora es GET para poder ver la lista de cursos en el navegador
$routes->get('cursos', 'Cursos::index');
// ...

// Rutas de Estudiantes
$routes->get('estudiantes', 'Estudiantes::index');
$routes->post('estudiantes/crear', 'Estudiantes::crear');
$routes->post('estudiantes/guardar', 'Estudiantes::guardar');
// ...

// Rutas de Inscripción
$routes->post('inscripcion/guardar', 'Inscripcion::guardar');
// ...
