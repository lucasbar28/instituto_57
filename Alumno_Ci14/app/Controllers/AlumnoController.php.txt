<?php

namespace App\Controllers;

use App\Models\AlumnoModel;
use CodeIgniter\Controller;

class AlumnoController extends Controller
{
    public function index()
    {
        $model = new AlumnoModel();
        $alumnos = $model->findAll();

        return view('alumnos/index', ['alumnos' => $alumnos]);
    }

    public function nuevo()
    {
        return view('alumnos/nuevo');
    }

    public function guardar()
    {
        $model = new AlumnoModel();

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'edad'   => $this->request->getPost('edad'),
        ];

        $model->insert($data);

        return redirect()->to('/alumnos')->with('mensaje', '¡Alumno registrado con éxito!');
    }

    public function editar($id)
    {
        $model = new AlumnoModel();
        $alumno = $model->find($id);

        if (!$alumno) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se encontró el alumno con ID: ' . $id);
        }

        return view('alumnos/editar', ['alumno' => $alumno]);
    }

    public function actualizar($id)
    {
        $model = new AlumnoModel();

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'edad'   => $this->request->getPost('edad'),
        ];

        $model->update($id, $data);

        return redirect()->to('/alumnos')->with('mensaje', '¡Alumno actualizado con éxito!');
    }

    public function eliminar($id)
    {
        $model = new AlumnoModel();
        $model->delete($id);

        return redirect()->to('/alumnos')->with('mensaje', '¡Alumno eliminado con éxito!');
    }
}