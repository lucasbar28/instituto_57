<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table      = 'categorias';
    protected $primaryKey = 'id_categoria';
    
    // Campos permitidos para la inserción/actualización
    protected $allowedFields = ['nombre', 'descripcion'];

    // --- TimeStamps ---
    protected $useTimestamps = true; 
    protected $createdField  = 'fecha_creacion'; 
    protected $updatedField  = 'fecha_actualizacion'; 
    
    // NUEVA LÍNEA: Asegura que el modelo use el formato de fecha adecuado para MySQL.
    protected $dateFormat    = 'datetime'; 
    // ------------------

    // Callback: Ejecuta la función 'guardarComoJSON' después de una inserción exitosa
    protected $afterInsert = ['guardarComoJSON'];

    protected function guardarComoJSON(array $data)
    {
        // El ID del registro insertado se encuentra en $data['id']
        $id_insertado = $data['id'] ?? null; 

        if ($id_insertado) {
            
            // Obtener el registro completo de la base de datos
            $registro = $this->find($id_insertado);

            // Codificar a formato JSON con formato legible
            $json_data = json_encode($registro, JSON_PRETTY_PRINT);
            
            // Definir la ruta del archivo
            $file_name = 'export_categoria_' . date('YmdHis') . '.json';
            $file_path = WRITEPATH . 'exports/' . $file_name;

            // Crear el directorio de exportación si no existe
            if (!is_dir(WRITEPATH . 'exports')) {
                mkdir(WRITEPATH . 'exports', 0777, true);
            }

            // Guardar el contenido en el archivo
            file_put_contents($file_path, $json_data);
        }
        
        // Es crucial retornar $data al final de un callback del modelo
        return $data;
    }
}
 