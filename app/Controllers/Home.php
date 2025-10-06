<?php 
namespace App\Controllers;

class Home extends BaseController
{
    // LÍNEA 7 REMOVIDA
    
    /**
     * Cargo la vista principal (Home) del sitio.
     * La ruta asociada a esta función es '/' en app/Config/Routes.php
     */
    public function index(): string
    {
        // La vista ahora se llama 'home.php', por lo que cargamos 'home'
        return view('home');
    }
} 