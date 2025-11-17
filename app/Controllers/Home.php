<?php 
namespace App\Controllers;

use App\Models\CarreraModel;
use App\Models\CategoriaModel;

class Home extends BaseController
{
    /**
     * Cargo la vista principal (Home) del sitio.
     * Si el usuario es administrador, muestra el dashboard de admin.
     * Si no, muestra el home público.
     */
    public function index(): string
    {
        $session = session();
        
        // Si el usuario está logueado y es administrador, mostrar dashboard
        if ($session->get('isLoggedIn') && $session->get('rol') === 'administrador') {
            $data = [
                'title' => 'Panel de Administración',
                'username' => $session->get('username'),
                'rol' => $session->get('rol')
            ];
            return view('admin/dashboard', $data);
        }
        
        // Si no es admin o no está logueado, mostrar home público con carreras
        $carreraModel = new CarreraModel();
        $categoriaModel = new CategoriaModel();
        
        $data = [
            'carreras' => $carreraModel->findAll(),
            'categorias' => $categoriaModel->findAll()
        ];
        
        return view('home', $data);
    }
} 