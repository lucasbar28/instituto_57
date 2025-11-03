<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfesorModel extends Model
{
    protected $table      = 'profesores';
    protected $primaryKey = 'id_profesor';
    protected $allowedFields = ['nombre_completo', 'especialidad', 'email', 'telefono', 'id_usuario'];

    // CORRECCIÓN: Tu tabla 'profesores' no tiene timestamps
    protected $useTimestamps = false;
    /*
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; 
    */

    protected $afterInsert = ['guardarComoJSON'];

    protected function guardarComoJSON(array $data)
    {
        $id_insertado = $data['id'] ?? null; 

        if ($id_insertado) {
            $registro = $this->find($id_insertado);

            if ($registro) {
                $json_data = json_encode($registro, JSON_PRETTY_PRINT);
                
                $file_name = 'export_profesor_' . date('YmdHis') . '.json';
                $file_path = WRITEPATH . 'exports/' . $file_name;

                if (!is_dir(WRITEPATH . 'exports')) {
                    mkdir(WRITEPATH . 'exports', 0777, true);
                }

                file_put_contents($file_path, $json_data);
            }
        }
        
        // CORRECCIÓN: Faltaba el return
        return $data;
    }
} 