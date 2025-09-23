<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\InscripcionModel;
use App\Models\AlumnoModel;
use App\Models\CursoModel;

class Inscripcion extends Controller
{
    public function guardar()
    {
        // Obtiene la instancia del modelo de inscripción
        $inscripcionModel = new InscripcionModel();

        // Obtiene los datos enviados por el formulario
        $data = [
            'id_alumno' => $this->request->getPost('id_alumno'),
            'id_curso'  => $this->request->getPost('id_curso')
        ];

        // Guarda el registro en la base de datos
        $inscripcionModel->save($data);

        // Opcional: Redirige al usuario a una página de confirmación
        return redirect()->to(base_url('inscripcion/exitosa'));
    }
} 