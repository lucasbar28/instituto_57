<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProfesorModel;

class Profesores extends Controller
{
    public function index()
    {
        // 1. Instancia el modelo
        $model = new ProfesorModel();

        // 2. Obtén todos los profesores de la tabla
        $data['profesores'] = $model->findAll();

        // 3. Pasa los datos a la vista y muéstrala
        return view('profesores', $data);
    }
} 