<?php

namespace App\Models;

use CodeIgniter\Model;

class CursoModel extends Model
{
    protected $table      = 'cursos';
    protected $primaryKey = 'id_curso';
    
    // CORREGIDO: Se añadió 'id_profesor' para que pueda ser guardado desde el formulario.
    protected $allowedFields = ['nombre', 'creditos', 'descripcion', 'id_carrera', 'id_profesor'];

    // --- TimeStamps (Para gestión automática de fechas) ---
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
            // Se debe volver a buscar el registro completo para incluir todos los campos (incluyendo timestamps)
            $registro = $this->find($data['id']);

            $json_data = json_encode($registro, JSON_PRETTY_PRINT);
            
            $file_name = 'export_curso_' . date('YmdHis') . '.json';
            $file_path = WRITEPATH . 'exports/' . $file_name;

            if (!is_dir(WRITEPATH . 'exports')) {
                mkdir(WRITEPATH . 'exports', 0777, true);
            }

            file_put_contents($file_path, $json_data);
        }
        
        // Es crucial retornar $data al final de un callback
        return $data;
    }
} 