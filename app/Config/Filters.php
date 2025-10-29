<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configuración de la ruta por defecto para todos los filtros.
     * Puede ser una string, una matriz de strings o un array asociativo.
     */
    public array $globals = [
        'before' => [
            // 'honeypot', // Descomentar si usas honeypot
            'csrf',
            // 'invalidchars', // Descomentar si usas invalidchars
        ],
        'after' => [
            'toolbar',
            // 'honeypot', // Descomentar si usas honeypot
            // 'secureheaders', // Descomentar si usas secureheaders
        ],
    ];

    /**
     * Lista de alias de filtros y la clase de implementación completa.
     * Esto le dice a CodeIgniter dónde buscar el código del filtro 'auth'.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        // -----------------------------------------------------------------
        // LA LÍNEA CRUCIAL QUE NECESITAS AÑADIR/VERIFICAR:
        // Asegúrate de que tu filtro 'AuthFilter' esté en 'app/Filters/AuthFilter.php'
        'auth'          => \App\Filters\AuthFilter::class, 
        // -----------------------------------------------------------------
    ];

    // ... (El resto del archivo se mantiene igual) ...
}
 