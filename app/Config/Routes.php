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

// --- PROFESORES ---
// Creamos un grupo general para el módulo 'profesores'
$routes->group('profesores', static function ($routes) {
    
    // Ruta principal (Dashboard/Lista): Accesible por Administrador y Profesor
    // Se corrige el filtro para permitir el acceso al profesor (rol:administrador,profesor)
    $routes->get('/', 'Profesores::index', ['filter' => 'role:administrador,profesor']); 

    // Rutas de CRUD: Solo accesibles por Administrador (usando un subgrupo para aplicar el filtro)
    $routes->group('/', ['filter' => 'role:administrador'], static function ($routes) {
        $routes->get('crear', 'Profesores::crear'); 
        $routes->post('guardar', 'Profesores::guardar');
        $routes->get('ver/(:num)', 'Profesores::ver/$1'); 
        $routes->get('editar/(:num)', 'Profesores::editar/$1'); 
        $routes->post('actualizar', 'Profesores::actualizar');
        $routes->get('eliminar/(:num)', 'Profesores::eliminar/$1'); 
    });
});


// --- CARRERAS (CRUD solo para Administrador) ---
// Aplicamos el filtro 'role:administrador' para proteger todo el grupo CRUD
$routes->group('carreras', ['filter' => 'role:administrador'], static function ($routes) {
    $routes->get('/', 'Carreras::index');
    $routes->get('crear', 'Carreras::crear');
    $routes->post('guardar', 'Carreras::guardar');
    $routes->get('editar/(:num)', 'Carreras::editar/$1');
    $routes->post('actualizar', 'Carreras::actualizar');
    $routes->get('eliminar/(:num)', 'Carreras::eliminar/$1');
});


// --- CURSOS (CRUD solo para Administrador) ---
// Aplicamos el filtro 'role:administrador' para proteger todo el grupo CRUD
$routes->group('cursos', ['filter' => 'role:administrador'], static function ($routes) {
    $routes->get('/', 'Cursos::index');
    $routes->get('crear', 'Cursos::crear');
    $routes->post('guardar', 'Cursos::guardar');
    $routes->get('editar/(:num)', 'Cursos::editar/$1');
    $routes->post('actualizar', 'Cursos::actualizar');
    $routes->get('eliminar/(:num)', 'Cursos::eliminar/$1');
});


// --- ESTUDIANTES (CRUD solo para Administrador) ---
// Aplicamos el filtro 'role:administrador' para proteger todo el grupo CRUD
$routes->group('estudiantes', ['filter' => 'role:administrador'], static function ($routes) {
    $routes->get('/', 'Estudiantes::index');
    $routes->get('crear', 'Estudiantes::crear');
    $routes->post('guardar', 'Estudiantes::guardar');
    $routes->get('ver/(:num)', 'Estudiantes::ver/$1');
    $routes->get('editar/(:num)', 'Estudiantes::editar/$1');
    $routes->post('actualizar', 'Estudiantes::actualizar');
    $routes->get('eliminar/(:num)', 'Estudiantes::eliminar/$1');
});

// --- Lógica de Inscripción) ---
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