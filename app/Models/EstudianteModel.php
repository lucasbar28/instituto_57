<?php

namespace App\Models;

use CodeIgniter\Model;

class EstudianteModel extends Model
{
    protected $table      = 'alumnos';
    protected $primaryKey = 'id_alumno';
    
    // CORRECCIÓN: Tu BD usa 'dni_matricula' y NO tiene 'estado'
    protected $allowedFields = ['nombre_completo', 'dni_matricula', 'email', 'telefono', 'id_usuario', 'id_carrera', 'anio_actual'];

    // CORRECCIÓN: Tu tabla 'alumnos' no tiene timestamps
    protected $useTimestamps = false; 
    /*
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; 
    */

    // CORRECCIÓN: Tu BD usa 'dni_matricula'
    protected $validationRules      = [
        'nombre_completo' => 'required|min_length[3]|max_length[100]',
        'dni_matricula'   => 'required|max_length[15]|is_unique[alumnos.dni_matricula,id_alumno,{id_alumno}]',
        'email'           => 'required|max_length[100]|valid_email|is_unique[alumnos.email,id_alumno,{id_alumno}]',
        'id_carrera'      => 'required|integer', 
        'telefono'        => 'permit_empty|max_length[20]',
    ];

    protected $afterInsert = ['guardarComoJSON'];

    protected function guardarComoJSON(array $data)
    {
        if (isset($data['id']) && $data['id'] > 0) {
            $registro = $this->find($data['id']);

            $json_data = json_encode($registro, JSON_PRETTY_PRINT);
            
            $file_name = 'export_alumno_' . date('YmdHis') . '.json';
            $file_path = WRITEPATH . 'exports/' . $file_name;

            if (!is_dir(WRITEPATH . 'exports')) {
                mkdir(WRITEPATH . 'exports', 0777, true);
            }

            file_put_contents($file_path, $json_data);
        }
        
        // CORRECCIÓN: Faltaba el return
        return $data;
    }
} 