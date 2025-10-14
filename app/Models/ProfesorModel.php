<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfesorModel extends Model
{
    protected $table      = 'profesores';
    protected $primaryKey = 'id_profesor';
    protected $allowedFields = ['nombre_completo', 'especialidad', 'email', 'telefono', 'id_usuario'];

    // --- TimeStamps (CORRECCIÓN: Se desactiva porque la tabla 'profesores' no tiene estas columnas) ---
    protected $useTimestamps = false; // <-- ¡ESTE ES EL CAMBIO CLAVE!
    // Las siguientes líneas se vuelven irrelevantes si useTimestamps es 'false', pero las dejo comentadas por si las necesitas en el futuro.
    /*
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; 
    */
    // ------------------------------------------------------------------------------------------------

    protected $afterInsert = ['guardarComoJSON'];

    protected function guardarComoJSON(array $data)
    {
        // Nota: En la inserción, $data['id'] es donde el modelo almacena el ID insertado.
        $id_insertado = $data['id'] ?? null; 

        if ($id_insertado) {
            $registro = $this->find($id_insertado);

            if ($registro) {
                $json_data = json_encode($registro, JSON_PRETTY_PRINT);
                
                // Formato de fecha para el nombre del archivo
                $file_name = 'export_profesor_' . date('YmdHis') . '.json';
                $file_path = WRITEPATH . 'exports/' . $file_name;

                if (!is_dir(WRITEPATH . 'exports')) {
                    // Asegura que el directorio exista
                    mkdir(WRITEPATH . 'exports', 0777, true);
                }

                file_put_contents($file_path, $json_data);
            }
        }
        
        // Es crucial retornar $data al final del callback
        return $data;
    }
}
