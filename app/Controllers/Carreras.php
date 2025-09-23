<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\CarreraModel;

class Carreras extends Controller
{
    public function index()
    {
        // 1. Instancia el modelo
        $model = new CarreraModel();

        // 2. Obtén todos los profesores de la tabla
        $data['carreras'] = $model->findAll();

        // 3. Pasa los datos a la vista y muéstrala
        return view('carreras', $data);
    }
} 