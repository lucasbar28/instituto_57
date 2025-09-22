<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Rutas para AlumnoController
$routes->get('alumnos', 'AlumnoController::index');
$routes->get('alumnos/nuevo', 'AlumnoController::nuevo');
$routes->post('alumnos/crear', 'AlumnoController::crear');
$routes->get('alumnos/editar/(:num)', 'AlumnoController::editar/$1');
$routes->post('alumnos/actualizar/(:num)', 'AlumnoController::actualizar/$1');
$routes->get('alumnos/eliminar/(:num)', 'AlumnoController::eliminar/$1');
