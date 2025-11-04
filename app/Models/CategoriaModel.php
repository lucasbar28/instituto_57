<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table      = 'categorias';
    protected $primaryKey = 'id_categoria';
    
    protected $allowedFields = ['nombre', 'descripcion'];

    // --- TimeStamps ---
    protected $useTimestamps = true; 
    protected $createdField  = 'fecha_creacion'; 
    protected $updatedField  = 'fecha_actualizacion'; 
    
    // CORRECCIÓN: Tu BD usa 'date', no 'datetime'
    protected $dateFormat    = 'date'; 
    // ------------------

    protected $afterInsert = ['guardarComoJSON'];

    protected function guardarComoJSON(array $data)
    {
        $id_insertado = $data['id'] ?? null; 

        if ($id_insertado) {
            $registro = $this->find($id_insertado);
            $json_data = json_encode($registro, JSON_PRETTY_PRINT);
            
            $file_name = 'export_categoria_' . date('YmdHis') . '.json';
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