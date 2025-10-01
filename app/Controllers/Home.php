<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Esto le dice a CodeIgniter que cargue el archivo app/Views/home.php
        return view('home'); 
    }
} 