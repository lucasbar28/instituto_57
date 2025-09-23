<?php

namespace App\Models;

use CodeIgniter\Model;

class InscripcionModel extends Model
{
    protected $table = 'inscripciones';
    protected $primaryKey = 'id_inscripcion';
    protected $allowedFields = ['id_alumno', 'id_curso'];
} 