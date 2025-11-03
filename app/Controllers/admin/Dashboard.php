<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    /**
     * Muestra la página principal del panel de administración.
     */
    public function index()
    {
        $session = session();
        
        // Datos para la vista
        $data = [
            'title' => 'Dashboard Administrativo',
            'username' => $session->get('nombre_usuario'),
            'rol' => $session->get('rol'),
            // Aquí podrías agregar conteos de usuarios, cursos, etc.
        ];

        // Se asume que la vista se llama 'admin/dashboard.php'
        return view('admin/dashboard', $data);
    }
}
