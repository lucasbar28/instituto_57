<?php

namespace App\Models;

use CodeIgniter\Model;

class EstudianteModel extends Model
{
    protected $table      = 'alumnos';
    protected $primaryKey = 'id_alumno';
    
    // CORREGIDO: Se usa 'dni' en lugar de 'dni_matricula'
    protected $allowedFields = ['nombre_completo', 'dni', 'email', 'telefono', 'id_usuario', 'id_carrera'];

    // --- TimeStamps (Añadido para gestión automática de fechas) ---
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; 
    // -----------------------------------------------------------------

    // Callback para guardar el registro como JSON después de la inserción
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
        // Es crucial retornar $data al final del callback
        return $data;
    }
} 