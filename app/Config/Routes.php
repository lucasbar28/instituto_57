<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'Login::index');
$routes->post('auth', 'Login::auth'); 
$routes->get('profesores', 'Profesores::index');
$routes->get('carreras', 'Carreras::index'); 
$routes->get('categorias', 'Categorias::index'); 
$routes->get('cursos', 'Cursos::index'); 
$routes->post('inscripcion/guardar', 'Inscripcion::guardar');
$routes->get('estudiantes/crear', 'Estudiantes::crear');
$routes->post('estudiantes/guardar', 'Estudiantes::guardar');
$routes->get('profesores/crear', 'Profesores::crear');
$routes->post('profesores/guardar', 'Profesores::guardar'); 
$routes->get('carreras/crear', 'Carreras::crear');
$routes->post('carreras/guardar', 'Carreras::guardar'); 