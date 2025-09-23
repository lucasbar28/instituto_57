<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\CategoriaModel;

class Categorias extends Controller
{
    public function index()
    {
        // 1. Instancia el modelo
        $model = new CategoriaModel();

        // 2. Obtén todas las categorías de la tabla
        $data['categorias'] = $model->findAll();

        // 3. Pasa los datos a la vista y muéstrala
        return view('categorias', $data);
    }
} 