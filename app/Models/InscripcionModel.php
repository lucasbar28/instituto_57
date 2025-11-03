<?php

namespace App\Models;

use CodeIgniter\Model;

class InscripcionModel extends Model
{
    protected $table = 'inscripciones';
    protected $primaryKey = 'id_inscripcion'; 
    protected $allowedFields = ['id_alumno', 'id_curso', 'fecha_inscripcion', 'estado'];

    // CORRECTO: Tu tabla SÍ tiene timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // CORRECCIÓN: Tu tabla SÍ tiene deleted_at, activamos SoftDeletes
    protected $useSoftDeletes = true; 
    protected $deletedField   = 'deleted_at';
    
    protected $validationRules = [
        'id_alumno' => 'required|integer',
        'id_curso'  => 'required|integer',
        // CORRECCIÓN: Tu BD usa 'Activo' como default, validamos 'Activo' o 'Inactivo'
        'estado'    => 'required|in_list[Activo,Inactivo]',
    ];
    
    protected $validationMessages = [
        'id_alumno' => [
            'required' => 'El alumno es obligatorio para la inscripción.',
        ],
        'id_curso' => [
            'required' => 'El curso es obligatorio para la inscripción.',
        ],
    ];
} 