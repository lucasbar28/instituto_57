<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InscripcionModel; 
use CodeIgniter\I18n\Time; 

// CORRECCIÓN CRÍTICA: La clase debe coincidir con el nombre del archivo (Inscripcion.php)
// y con el nombre en la ruta (Inscripcion::inscribir)
class Inscripcion extends BaseController
{
    protected $inscripcionModel;
    protected $session;

    public function __construct()
    {
        // Se llama al constructor del padre si es necesario, aunque en BaseController no hace nada especial.
        // parent::__construct(); 
        
        $this->inscripcionModel = new InscripcionModel();
        // Cargar el servicio de sesión directamente
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
        
        // Verificar si la solicitud es AJAX para devolver una respuesta JSON limpia (opcional, pero útil)
        // if (!$this->request->isAJAX()) {
        //     //return $this->response->setStatusCode(405)->setBody('Method Not Allowed');
        // }

        // 2. Preparar el array de datos para el modelo, incluyendo campos no automáticos
        $dataToSave = [
            'id_alumno'         => $data['id_alumno'] ?? null,
            'id_curso'          => $data['id_curso'] ?? null,
            // Asumo que tu tabla tiene 'fecha_inscripcion' como DATE o DATETIME
            'fecha_inscripcion' => Time::now()->toDateString(), 
            'estado'            => 'Activo', // Estado por defecto para nuevas inscripciones
        ];
        
        // 3. Validar y Guardar los datos
        if ($this->inscripcionModel->insert($dataToSave) === false) {
            // Error en la inserción o validación fallida
            $errors = $this->inscripcionModel->errors();
            
            // Si hay errores de validación, los guardamos
            if (!empty($errors)) {
                $errorMessage = 'No se pudo completar la inscripción. Errores: ' . implode(', ', array_values($errors));
                $this->session->setFlashdata('errors', $errors);
            } else {
                // Si la inserción falló por otra razón (ej. restricción de BD no capturada por rules)
                $errorMessage = '❌ Error de base de datos al guardar la inscripción. Consulte los logs.';
            }

            $this->session->setFlashdata('error', $errorMessage);
            return redirect()->back()->withInput();
        }

        // 4. Éxito
        $this->session->setFlashdata('mensaje', '✅ Alumno inscrito correctamente.');
        return redirect()->to(base_url('estudiantes')); 
    }
    
    // Aquí puedes añadir otros métodos como index() para listar inscripciones, etc.
}
 