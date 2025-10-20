<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Rutas de Login y Autenticación
$routes->get('login', 'Login::index');
$routes->post('login/auth', 'Login::auth');
$routes->get('logout', 'Login::logout');

// ==========================================================================
// RUTAS DE GESTIÓN (CRUD COMPLETO)
// ==========================================================================

// --- PROFESORES ---
$routes->group('profesores', static function ($routes) {
    // Listar todos
    $routes->get('/', 'Profesores::index'); 
    // Crear (Formulario GET y Guardar POST)
    $routes->get('crear', 'Profesores::crear');
    $routes->post('guardar', 'Profesores::guardar');
    // Editar (Formulario GET)
    $routes->get('editar/(:num)', 'Profesores::editar/$1'); 
    // Actualizar (Procesar formulario POST)
    $routes->post('actualizar', 'Profesores::actualizar');
    // Eliminar (Eliminación lógica/física)
    $routes->get('eliminar/(:num)', 'Profesores::eliminar/$1'); 
});


// --- CARRERAS ---
$routes->group('carreras', static function ($routes) {
    $routes->get('/', 'Carreras::index');
    $routes->get('crear', 'Carreras::crear');
    $routes->post('guardar', 'Carreras::guardar');
    $routes->get('editar/(:num)', 'Carreras::editar/$1');
    $routes->post('actualizar', 'Carreras::actualizar');
    $routes->get('eliminar/(:num)', 'Carreras::eliminar/$1');
});


// --- CATEGORÍAS ---
$routes->group('categorias', static function ($routes) {
    $routes->get('/', 'Categorias::index'); 
    $routes->get('crear', 'Categorias::crear');
    $routes->post('guardar', 'Categorias::guardar');
    $routes->get('editar/(:num)', 'Categorias::editar/$1');
    $routes->post('actualizar', 'Categorias::actualizar');
    $routes->get('eliminar/(:num)', 'Categorias::eliminar/$1');
});


// --- CURSOS ---
$routes->group('cursos', static function ($routes) {
    $routes->get('/', 'Cursos::index');
    $routes->get('crear', 'Cursos::crear');
    $routes->post('guardar', 'Cursos::guardar');
    $routes->get('editar/(:num)', 'Cursos::editar/$1');
    $routes->post('actualizar', 'Cursos::actualizar');
    $routes->get('eliminar/(:num)', 'Cursos::eliminar/$1');
});


// --- ESTUDIANTES (Alumnos) ---
$routes->group('estudiantes', static function ($routes) {
    $routes->get('/', 'Estudiantes::index');
    $routes->get('crear', 'Estudiantes::crear');
    $routes->post('guardar', 'Estudiantes::guardar');
    $routes->get('editar/(:num)', 'Estudiantes::editar/$1');
    $routes->post('actualizar', 'Estudiantes::actualizar');
    $routes->get('eliminar/(:num)', 'Estudiantes::eliminar/$1');
});


// --- INSCRIPCIONES ---
// Solución al error 404: 'POST: inscripciones/inscribir'
$routes->group('inscripciones', static function ($routes) {
    // RUTA DE INSCRIPCIÓN RÁPIDA
    $routes->post('inscribir', 'Inscripciones::inscribir');
});

// Nota: La ruta original 'inscripcion/guardar' (singular) fue eliminada para evitar duplicidad 
// y seguir la convención de 'inscripciones' (plural) que usamos ahora.
