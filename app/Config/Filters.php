<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
// Importaciones de filtros nativos
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
// Importamos los filtros personalizados
use App\Filters\RoleFilter; 
use App\Filters\AuthFilter; // <--- Importamos la clase AuthFilter que creamos

class Filters extends BaseConfig
{
    /**
     * Define las reglas de alias para los filtros
     *
     * @var array
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        
        // CORRECCIÓN: Definir 'auth' para el filtro de autenticación simple (AuthFilter)
        // y 'role' para el filtro que necesita argumentos de rol (RoleFilter).
        'auth'          => AuthFilter::class,
        'role'          => RoleFilter::class, // <--- Este es el alias que tus rutas esperan
    ];

    /**
     * Lista de filtros que se aplican a cada grupo de rutas.
     * (El resto de tu archivo es correcto)
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf', 
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        // Esta sección debe permanecer vacía para aplicar los filtros con argumentos en Routes.php
    ];

    public array $excludes = [];
}
 