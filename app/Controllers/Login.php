<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;

class Login extends Controller
{
    public function index()
    {
        // Muestra la vista del formulario de login
        return view('login');
    }
} 