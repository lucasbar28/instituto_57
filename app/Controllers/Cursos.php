<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\CursoModel;

class Cursos extends Controller
{
    public function index()
    {
        // 1. Instancia el modelo
        $model = new CursoModel();

        // 2. Obtén todos los cursos de la tabla
        $data['cursos'] = $model->findAll();

        // 3. Pasa los datos a la vista y muéstrala
        return view('cursos', $data);
    }
} 