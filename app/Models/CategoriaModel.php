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
    // Si tu tabla 'categorias' tiene las columnas 'fecha_creacion' y 'fecha_actualizacion'
    protected $useTimestamps = true; 
    protected $createdField  = 'fecha_creacion'; 
    protected $updatedField  = 'fecha_actualizacion'; 
    // ------------------

    // Callback: Ejecuta la función 'guardarComoJSON' después de una inserción exitosa
    protected $afterInsert = ['guardarComoJSON'];

    protected function guardarComoJSON(array $data)
    {
        // El ID del registro insertado se encuentra en $data['id']
        if (isset($data['id']) && $data['id'] > 0) {
            
            // Obtener el registro completo de la base de datos
            $registro = $this->find($data['id']);

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