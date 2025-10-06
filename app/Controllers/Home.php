<?php

namespace App\Controllers;

class Home extends BaseController
{
 HEAD
    /**
     * Carga la vista principal (Home) del sitio.
     * La ruta asociada a esta función es '/' en app/Config/Routes.php
     */
    public function index(): string
    {
        // La vista ahora se llama 'home.php', por lo que cargamos 'home'
        return view('home');
    }
}
    public function index()
    {
        // Esto le dice a CodeIgniter que cargue el archivo app/Views/home.php
        return view('home'); 
    }
} 

