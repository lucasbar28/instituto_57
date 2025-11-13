<?php 
namespace App\Controllers;

class Home extends BaseController
{
    // LÍNEA 7 REMOVIDA
    
    /**
     * Cargo la vista principal (Home) del sitio.
     * La ruta asociada a esta función es '/' en app/Config/Routes.php
     */
    public function index()
    {
    // Mostrar alert una vez (flash)
    session()->setFlashdata('show_alert', true);
    return view('home');
    }
} 