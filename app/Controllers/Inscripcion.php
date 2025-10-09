<?php

namespace App\Controllers;

use App\Models\InscripcionModel;
use CodeIgniter\Controller;
use CodeIgniter\Database\Exceptions\DatabaseException; 

class Inscripcion extends BaseController
{
    /**
     * Procesa los datos del formulario de inscripción y guarda el registro.
     * La inscripción se espera que provenga de la vista 'estudiantes' (que muestra un formulario de inscripción rápida).
     */
    public function guardar()
    {
        // 1. Obtiene los datos enviados por el formulario
        $data = [
            'id_alumno' => $this->request->getPost('id_alumno'),
            'id_curso'  => $this->request->getPost('id_curso')
        ];

        // 2. --- Reglas de Validación ---
        if (! $this->validate([
            'id_alumno' => 'required|integer',
            'id_curso'  => 'required|integer|is_unique[inscripciones.id_curso,id_alumno,{id_alumno}]', 
        ],
        // Mensajes personalizados
        [
            'id_curso' => [
                'is_unique' => '❌ Este alumno ya se encuentra inscrito en el curso seleccionado.'
            ],
            'id_alumno' => [
                'required' => 'Debe seleccionar un alumno.',
            ],
            'id_curso' => [
                'required' => 'Debe seleccionar un curso.',
            ]
        ])) {
            // Si la validación falla (ej. duplicado o campo vacío), regresa a la página anterior
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Obtiene la instancia del modelo de inscripción
        $inscripcionModel = new InscripcionModel();
        
        // 4. Guarda el registro en la base de datos
        // Usamos save() ya que el modelo maneja internamente la fecha_inscripcion
        $inscripcionModel->save($data);

        // 5. Redirecciona con mensaje de éxito (flash data)
        return redirect()->back()->with('mensaje', '✅ Inscripción registrada con éxito!');
    }

    /**
     * Elimina un registro de inscripción basado en el ID de inscripción.
     * Esto desinscribe a un alumno de un curso.
     * @param int $id ID de la inscripción a eliminar (id_inscripcion).
     */
    public function eliminar($id)
    {
        $inscripcionModel = new InscripcionModel();
        
        try {
            // 1. Intentar eliminar el registro
            $deleted = $inscripcionModel->delete($id);

            if (!$deleted) {
                // Si delete retorna falso, el ID no existía o hubo un error silencioso.
                 return redirect()->back()->with('error', '❌ Error al desinscribir: No se encontró la inscripción o el ID es inválido.');
            }

            // 2. Redireccionar con mensaje de éxito
            return redirect()->back()->with('mensaje', '🗑️ Desinscripción realizada con éxito. El alumno ya no está en el curso.');

        } catch (DatabaseException $e) {
            log_message('error', 'Error al eliminar inscripción: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Error de base de datos al desinscribir. Por favor, inténtelo de nuevo.');
        }
    }
}
 