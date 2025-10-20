<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InscripcionModel; 
use CodeIgniter\I18n\Time; 

// EL NOMBRE DE LA CLASE DEBE SER 'Inscripciones' (Plural, con 'I' mayúscula)
class Inscripciones extends BaseController
{
    protected $inscripcionModel;
    protected $session;

    public function __construct()
    {
        $this->inscripcionModel = new InscripcionModel();
        $this->session = \Config\Services::session(); 
    }

    /**
     * Procesa la solicitud POST para inscribir a un alumno en un curso.
     * RUTA: POST /inscripciones/inscribir
     */
    public function inscribir()
    {
        // 1. Recibir los datos del formulario (deben tener estos nombres en el formulario HTML)
        $data = $this->request->getPost(['id_alumno', 'id_curso']);
        
        // 2. Preparar el array de datos para el modelo, incluyendo campos no automáticos
        $dataToSave = [
            'id_alumno'         => $data['id_alumno'] ?? null,
            'id_curso'          => $data['id_curso'] ?? null,
            'fecha_inscripcion' => Time::now()->toDateString(), 
            'estado'            => 'Activo', // Estado por defecto para nuevas inscripciones
        ];

        // 3. Validar los datos antes de intentar la inserción
        if (!$this->inscripcionModel->validate($dataToSave)) {
            $this->session->setFlashdata('errors', $this->inscripcionModel->errors());
            $this->session->setFlashdata('error', 'No se pudo completar la inscripción. Verifique los datos de alumno y curso.');
            return redirect()->back()->withInput();
        }

        // 4. Intentar guardar la inscripción
        try {
            if ($this->inscripcionModel->insert($dataToSave)) {
                $this->session->setFlashdata('success', '✅ Alumno inscrito correctamente.');
                // Redirigir a la vista de estudiantes
                return redirect()->to(base_url('estudiantes')); 
            } else {
                $this->session->setFlashdata('error', '❌ Error de base de datos al guardar la inscripción.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            $this->session->setFlashdata('error', '❌ Error al procesar la inscripción: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
