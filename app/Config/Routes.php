<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Rutas de Login y Autenticación
$routes->get('login', 'Login::index');
$routes->post('login', 'Login::auth'); 
$routes->get('logout', 'Login::logout');

// ==========================================================================
// RUTAS DE GESTIÓN (CRUD COMPLETO)
// ==========================================================================

// --- PROFESORES ---
$routes->group('profesores', static function ($routes) {
    // Listar todos
    $routes->get('/', 'Profesores::index'); 
    
    // Crear (Formulario GET)
    // CONSISTENCIA: Cambiado 'create' a 'crear'
    $routes->get('crear', 'Profesores::crear'); 
    // Guardar (Procesar formulario POST)
    $routes->post('guardar', 'Profesores::guardar');
    
    // Mostrar/Ver detalle de un registro (GET: /profesores/ver/4)
    $routes->get('ver/(:num)', 'Profesores::ver/$1'); 
    
    // Editar (Formulario GET para editar)
    $routes->get('editar/(:num)', 'Profesores::editar/$1'); 
    // Actualizar (Procesar formulario POST)
    $routes->post('actualizar', 'Profesores::actualizar');
    
    // Eliminar
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
$routes->group('inscripciones', static function ($routes) {
    // Procesar la inscripción de un alumno a un curso (POST)
    $routes->post('inscribir', 'Inscripcion::inscribir');
    
    // Nueva ruta: Desinscribir un alumno (Eliminación lógica/Soft Delete) (GET)
    $routes->get('desinscribir/(:num)', 'Inscripcion::desinscribir/$1');
});

// Rutas de Autenticación y Registro
$routes->get('registro', 'Registro::index');
$routes->post('registro/alumno', 'Registro::registroAlumno'); 
// ==========================================================================
// RUTAS DE ADMINISTRACIÓN (Dashboard)
// ==========================================================================

$routes->group('admin', ['filter' => 'auth:administrador'], static function ($routes) {
    // Ruta principal del Dashboard
    // Llama al método index() del controlador Admin/Dashboard
    $routes->get('dashboard', 'Admin\Dashboard::index'); 
});
 