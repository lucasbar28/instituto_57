<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Perfil; // Importamos el controlador Perfil para usarlo en las rutas

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Rutas de Login y Autenticación
$routes->get('login', 'Login::index');
$routes->post('login', 'Login::auth'); 
$routes->get('logout', 'Login::logout');

// ==========================================================================
// RUTAS DE GESTIÓN (CRUD COMPLETO) - APLICANDO FILTROS DE ROLES
// ==========================================================================

// --- PROFESORES (CRUD solo para Administrador) ---
// Aplicamos el filtro 'role:administrador' para proteger todo el grupo CRUD
$routes->group('profesores', ['filter' => 'role:administrador'], static function ($routes) {
    $routes->get('/', 'Profesores::index'); 
    $routes->get('crear', 'Profesores::crear'); 
    $routes->post('guardar', 'Profesores::guardar');
    $routes->get('ver/(:num)', 'Profesores::ver/$1'); 
    $routes->get('editar/(:num)', 'Profesores::editar/$1'); 
    $routes->post('actualizar', 'Profesores::actualizar');
    $routes->get('eliminar/(:num)', 'Profesores::eliminar/$1'); 
});


// --- CARRERAS (CRUD solo para Administrador) ---
$routes->group('carreras', ['filter' => 'role:administrador'], static function ($routes) {
    $routes->get('/', 'Carreras::index');
    $routes->get('crear', 'Carreras::crear');
    $routes->post('guardar', 'Carreras::guardar');
    $routes->get('editar/(:num)', 'Carreras::editar/$1');
    $routes->post('actualizar', 'Carreras::actualizar');
    $routes->get('eliminar/(:num)', 'Carreras::eliminar/$1');
});


// --- CATEGORÍAS (CRUD solo para Administrador) ---
$routes->group('categorias', ['filter' => 'role:administrador'], static function ($routes) {
    $routes->get('/', 'Categorias::index'); 
    $routes->get('crear', 'Categorias::crear');
    $routes->post('guardar', 'Categorias::guardar');
    $routes->get('editar/(:num)', 'Categorias::editar/$1');
    $routes->post('actualizar', 'Categorias::actualizar');
    $routes->get('eliminar/(:num)', 'Categorias::eliminar/$1');
});


// --- CURSOS (CRUD para Administrador y vista para Profesor/Alumno si es necesario,
//             pero se protege el CRUD completo solo para Admin) ---
$routes->group('cursos', ['filter' => 'role:administrador'], static function ($routes) {
    // Si necesitas que profesores/alumnos vean la lista, se haría:
    // $routes->get('/', 'Cursos::index', ['filter' => 'role:administrador,profesor,alumno']);
    $routes->get('/', 'Cursos::index');
    $routes->get('crear', 'Cursos::crear');
    $routes->post('guardar', 'Cursos::guardar');
    $routes->get('editar/(:num)', 'Cursos::editar/$1');
    $routes->post('actualizar', 'Cursos::actualizar');
    $routes->get('eliminar/(:num)', 'Cursos::eliminar/$1');
});


// --- ESTUDIANTES (Alumnos) (CRUD solo para Administrador) ---
$routes->group('estudiantes', ['filter' => 'role:administrador'], static function ($routes) {
    $routes->get('/', 'Estudiantes::index');
    $routes->get('crear', 'Estudiantes::crear');
    $routes->post('guardar', 'Estudiantes::guardar');
    $routes->get('editar/(:num)', 'Estudiantes::editar/$1');
    $routes->post('actualizar', 'Estudiantes::actualizar');
    $routes->get('eliminar/(:num)', 'Estudiantes::eliminar/$1');
});


// --- INSCRIPCIONES (Acceso a la lógica de Inscripción) ---
// Esto dependerá de quién puede inscribir. Asumo que es el Administrador.
$routes->group('inscripciones', ['filter' => 'role:administrador'], static function ($routes) {
    $routes->post('inscribir', 'Inscripcion::inscribir');
    $routes->get('desinscribir/(:num)', 'Inscripcion::desinscribir/$1');
});


// Rutas de Autenticación y Registro (No necesitan filtro, son públicas)
$routes->get('registro', 'Registro::index');
$routes->post('registro/alumno', 'Registro::registroAlumno'); 

// ==========================================================================
// RUTAS DE ADMINISTRACIÓN (Dashboard)
// ==========================================================================
// Ahora usamos el filtro 'role' para una mejor gestión de roles
$routes->group('admin', ['filter' => 'role:administrador'], static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index'); 
});

// ==========================================================================
// RUTAS DE PERFIL (CAMBIO DE CONTRASEÑA)
// ==========================================================================
// Se mantiene el filtro 'auth', pero el RoleFilter (que incluye la lógica
// de cambio obligatorio) se encargará de forzar la redirección si es necesario.
$routes->get('perfil/cambio-contrasena', 'Perfil::cambioContrasena', ['filter' => 'auth']);
$routes->post('perfil/actualizar-contrasena', 'Perfil::actualizarContrasena', ['filter' => 'auth']);
 