<?php

namespace App\Models;

use CodeIgniter\Model;

class CarreraModel extends Model
{
    protected $table      = 'carreras';
    protected $primaryKey = 'id_carrera';
    
    // Campos permitidos para inserción y actualización
    protected $allowedFields = ['nombre_carrera', 'duracion', 'modalidad', 'id_categoria'];

    // --- TimeStamps (Añadido para gestión automática de fechas) ---
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; // Útil si usas Soft Delete
    // -----------------------------------------------------------------

    // Callback para guardar el registro como JSON después de la inserción
    protected $afterInsert = ['guardarComoJSON'];

    protected function guardarComoJSON(array $data)
    {
        // El ID del registro insertado se encuentra en $data['id']
        if (isset($data['id']) && $data['id'] > 0) {
            // Se debe volver a buscar el registro completo para incluir todos los campos (incluyendo timestamps)
            $registro = $this->find($data['id']);

            $json_data = json_encode($registro, JSON_PRETTY_PRINT);
            
            $file_name = 'export_carrera_' . date('YmdHis') . '.json';
            $file_path = WRITEPATH . 'exports/' . $file_name;

            // Asegura que el directorio WRITEPATH/exports exista
            if (!is_dir(WRITEPATH . 'exports')) {
                mkdir(WRITEPATH . 'exports', 0777, true);
            }

            // Guarda el contenido JSON en el archivo
            file_put_contents($file_path, $json_data);
        }
    }
} 