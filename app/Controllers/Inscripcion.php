<?php

namespace App\Controllers;

use App\Models\InscripcionModel;
use CodeIgniter\Controller;

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
        // Se valida que ambos IDs existan y, más importante, que la combinación
        // de id_alumno + id_curso no exista ya en la tabla 'inscripciones'.
        if (! $this->validate([
            'id_alumno' => 'required|integer',
            'id_curso'  => 'required|integer|is_unique[inscripciones.id_curso,id_alumno,{id_alumno}]', 
            // La regla 'is_unique' compleja comprueba: 
            // 1. Unicidad de 'id_curso'
            // 2. Filtrando por 'id_alumno' (la variable {id_alumno} se toma del array $data)
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
        $inscripcionModel->save($data);

        // 5. Redirecciona con mensaje de éxito (flash data)
        return redirect()->back()->with('mensaje', '✅ Inscripción registrada con éxito!');
        // Se redirige hacia atrás (back) asumiendo que el formulario está en estudiantes/index o cursos/index
    }
}
 