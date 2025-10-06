<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// -----------------------------------------------------------
// RUTAS DE AUTENTICACIÓN (LOGIN/LOGOUT)
// -----------------------------------------------------------

// Carga la vista de login
$routes->get('login', 'Login::index');
// Procesa las credenciales
$routes->post('auth', 'Login::auth');
// Cierra la sesión
$routes->get('logout', 'Login::logout');
$routes->post('login/auth', 'Login::auth'); 


// -----------------------------------------------------------
// RUTAS DE GESTIÓN (Protegidas por 'auth' filter)
// Se aplica el filtro 'auth' a todas las rutas dentro de este grupo.
// -----------------------------------------------------------

$routes->group('/', ['filter' => 'auth'], function ($routes) {
    
    // --- CARRERAS ---
    $routes->get('carreras', 'Carreras::index');
    $routes->get('carreras/crear', 'Carreras::crear');
    $routes->post('carreras/guardar', 'Carreras::guardar');
    // Rutas de edición y eliminación (pueden requerir un ID)
    $routes->get('carreras/editar/(:num)', 'Carreras::editar/$1');
    $routes->post('carreras/actualizar', 'Carreras::actualizar');
    $routes->get('carreras/eliminar/(:num)', 'Carreras::eliminar/$1');

    // --- CATEGORÍAS ---
    $routes->get('categorias', 'Categorias::index');
    $routes->get('categorias/crear', 'Categorias::crear');
    $routes->post('categorias/guardar', 'Categorias::guardar');
    // Rutas de edición y eliminación si son necesarias
    $routes->get('categorias/editar/(:num)', 'Categorias::editar/$1');
    $routes->get('categorias/eliminar/(:num)', 'Categorias::eliminar/$1');
    
    // --- ESTUDIANTES (Alumnos) ---
    $routes->get('estudiantes', 'Estudiantes::index');
    $routes->get('estudiantes/crear', 'Estudiantes::crear');
    $routes->post('estudiantes/guardar', 'Estudiantes::guardar');
    
    // --- PROFESORES ---
    $routes->get('profesores', 'Profesores::index');
    $routes->get('profesores/crear', 'Profesores::crear');
    $routes->post('profesores/guardar', 'Profesores::guardar');
    
    // --- CURSOS ---
    $routes->get('cursos', 'Cursos::index');
    $routes->get('cursos/crear', 'Cursos::crear');
    $routes->post('cursos/guardar', 'Cursos::guardar');
    
    // --- INSCRIPCIONES ---
    // La inscripción se guarda desde la vista de Estudiantes/Cursos
    $routes->post('inscripcion/guardar', 'Inscripcion::guardar');
    
});
 