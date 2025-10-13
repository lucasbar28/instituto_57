<?php

namespace App\Models;

use CodeIgniter\Model;

class InscripcionModel extends Model
{
    protected $table      = 'inscripciones';
    protected $primaryKey = 'id_inscripcion';
    protected $allowedFields = ['id_alumno', 'id_curso'];

    // --- TimeStamps (Añadido para gestión automática de fechas) ---
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; 
    // -----------------------------------------------------------------

    // Define el evento que se activa después de una inserción
    protected $afterInsert = ['guardarComoJSON'];

    // Esta función se ejecuta automáticamente después de insertar una inscripción
    protected function guardarComoJSON(array $data)
    {
        if (isset($data['id']) && $data['id'] > 0) {
            // Busca el registro completo usando el ID que se acaba de insertar
            $registro = $this->find($data['id']);

            // Convierte el array de PHP a una cadena JSON con formato legible
            $json_data = json_encode($registro, JSON_PRETTY_PRINT);
            
            // Define la ruta y el nombre del archivo
            $file_name = 'export_inscripcion_' . date('YmdHis') . '.json';
            $file_path = WRITEPATH . 'exports/' . $file_name;

            // Asegúrate de que el directorio de exportación exista
            if (!is_dir(WRITEPATH . 'exports')) {
                mkdir(WRITEPATH . 'exports', 0777, true);
            }

            // Guarda el archivo
            file_put_contents($file_path, $json_data);
        }
        
        // Es crucial retornar $data al final del callback
        return $data;
    }
} 