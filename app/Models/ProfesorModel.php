<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfesorModel extends Model
{
    protected $table = 'profesores';
    protected $primaryKey = 'id_profesor';
    protected $allowedFields = ['nombre_completo', 'especialidad', 'email', 'telefono'];
} 