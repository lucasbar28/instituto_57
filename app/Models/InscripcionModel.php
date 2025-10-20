<?php

namespace App\Models;

use CodeIgniter\Model;

class InscripcionModel extends Model
{
    // Nombre de la tabla
    protected $table = 'inscripciones';
    
    // Clave primaria de la tabla
    protected $primaryKey = 'id_inscripcion'; 

    // Campos que se pueden rellenar y guardar
    // Corresponden a los campos de tu tabla (excluyendo PK y Timestamps automáticos)
    protected $allowedFields = ['id_alumno', 'id_curso', 'fecha_inscripcion', 'estado'];

    // Usaremos timestamps para created_at y updated_at, ya que están en tu tabla
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // No usaremos Soft Deletes (deleted_at), ya que no está en tu tabla de inscripciones
    protected $useSoftDeletes = false; 
    
    // Reglas de validación básicas
    protected $validationRules = [
        'id_alumno' => 'required|integer',
        'id_curso'  => 'required|integer',
        'estado'    => 'required|string',
    ];
    
    // Mensajes de error personalizados para las validaciones
    protected $validationMessages = [
        'id_alumno' => [
            'required' => 'El alumno es obligatorio para la inscripción.',
        ],
        'id_curso' => [
            'required' => 'El curso es obligatorio para la inscripción.',
        ],
    ];
}
