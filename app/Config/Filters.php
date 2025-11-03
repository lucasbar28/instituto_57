<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
// Importaciones de filtros nativos
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
// Importamos el filtro personalizado para roles
use App\Filters\RoleFilter; 

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
        'auth'          => RoleFilter::class, // <-- Alias del filtro de roles
    ];

    /**
     * Lista de filtros que se aplican a cada grupo de rutas.
     *
     * @var array
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

    /**
     * Lista de clases de filtro a aplicar a métodos HTTP específicos.
     *
     * @var array
     */
    public array $methods = [];

    /**
     * Filtros que se aplican a grupos específicos de rutas, 
     * usados por el método $routes->group().
     *
     * Esta propiedad es usada por el sistema de ruteo, pero tu la definiste
     * para organizar tus rutas protegidas.
     * * Nota: Normalmente, solo se usa 'aliases' y 'groups' en Routes.php,
     * no es habitual definir todas las rutas protegidas aquí.
     * * @var array
     */
    public array $filters = [
        'auth' => [
            'before' => [
                'dashboard', 
                'admin/*',
                'profesor/*',
                
                'alumnos', 'alumnos/*',
                'profesores', 'profesores/*',
                'carreras', 'carreras/*',
                'categorias', 'categorias/*',
                'cursos', 'cursos/*',
                'calendario', 'calendario/*',
                'campus', 'campus/*',
            ],
        ],
    ];

    /**
     * Lista de filtros para ignorar en ciertas rutas.
     *
     * @var array
     */
    public array $excludes = [];
}
 