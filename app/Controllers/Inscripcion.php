<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InscripcionModel; 
use CodeIgniter\I18n\Time; 

class Inscripcion extends BaseController
{
    protected $inscripcionModel;
    protected $session;
    protected $db; // Añadimos la propiedad de base de datos para consultas específicas si se necesitan

    public function __construct()
    {
        $this->inscripcionModel = new InscripcionModel();
        $this->session = \Config\Services::session(); 
        // Inicializa la conexión a la base de datos
        $this->db = \Config\Database::connect();
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
            // Usamos Time::now() para asegurar consistencia
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
    
    /**
     * Realiza la desinscripción (Soft Delete) de la última inscripción activa de un alumno.
     * RUTA: GET /inscripciones/desinscribir/(:num)
     * @param int $id_alumno ID del alumno a desinscribir.
     */
    public function desinscribir(int $id_alumno)
    {
        // 1. Buscar la última inscripción activa del alumno (la más reciente)
        // Buscamos la inscripción con 'deleted_at IS NULL' (activa)
        $ultimaInscripcion = $this->inscripcionModel
            ->where('id_alumno', $id_alumno)
            ->where('deleted_at IS NULL') 
            ->orderBy('fecha_inscripcion', 'DESC')
            ->first();

        if (empty($ultimaInscripcion)) {
            // Si no encuentra una inscripción activa, redirige con un mensaje
            $this->session->setFlashdata('error', 'El alumno no tiene inscripciones activas para desinscribir.');
            return redirect()->to(base_url('estudiantes'));
        }

        // 2. Realizar el Soft Delete usando el ID de la inscripción
        // CodeIgniter, gracias a $useSoftDeletes = true en el modelo, marcará 'deleted_at' con la fecha actual.
        // Además, actualizamos el campo 'estado' a 'Inactivo' para claridad, aunque SoftDelete ya lo "oculta".
        
        $idInscripcion = $ultimaInscripcion['id_inscripcion'];
        
        // Hacemos un update explícito del estado antes del delete (opcional, pero ayuda a la lógica)
        $this->inscripcionModel->update($idInscripcion, ['estado' => 'Inactivo']);

        // Ejecutamos el soft delete (coloca la fecha en deleted_at)
        if ($this->inscripcionModel->delete($idInscripcion)) {
            $this->session->setFlashdata('mensaje', '✅ Desinscripción realizada con éxito. El registro se ha movido al historial.');
            return redirect()->to(base_url('estudiantes'));
        } else {
            $this->session->setFlashdata('error', '❌ Error al procesar la desinscripción. Intente de nuevo.');
            return redirect()->to(base_url('estudiantes'));
        }
    }
}
