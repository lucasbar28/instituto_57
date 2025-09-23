<?php

namespace App\Models;

use CodeIgniter\Model;

class CarreraModel extends Model
{
    protected $table = 'carreras';
    protected $primaryKey = 'id_carrera';
    protected $allowedFields = ['nombre_carrera', 'duracion', 'modalidad', 'id_categoria'];
} 