<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InscripcionModel; // Asegúrate de que el path sea correcto
use CodeIgniter\I18n\Time; // Para manejar la fecha de forma limpia

class Inscripciones extends BaseController
{
    protected $inscripcionModel;
    protected $session;

    public function __construct()
    {
        // Instancia del modelo de Inscripciones
        $this->inscripcionModel = new InscripcionModel();
        // Inicialización del servicio de sesión para mensajes flash
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
            // Usamos Time::now()->toDateString() para obtener la fecha en formato 'YYYY-MM-DD' 
            // que coincide con la columna 'fecha_inscripcion' de tu tabla
            'fecha_inscripcion' => Time::now()->toDateString(), 
            'estado'            => 'Activo', // Estado por defecto para nuevas inscripciones
        ];

        // 3. Validar los datos antes de intentar la inserción
        if (!$this->inscripcionModel->validate($dataToSave)) {
            // Si la validación falla (ej. faltan id_alumno o id_curso)
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
                // Esto podría ocurrir por un error de base de datos no capturado por try/catch
                $this->session->setFlashdata('error', '❌ Error de base de datos al guardar la inscripción.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            // Capturar cualquier excepción de base de datos (ej. clave foránea inexistente)
            // En un entorno de producción, podrías registrar $e->getMessage()
            $this->session->setFlashdata('error', '❌ Error al procesar la inscripción: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
