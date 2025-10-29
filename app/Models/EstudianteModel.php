<?php

namespace App\Models;

use CodeIgniter\Model;

class EstudianteModel extends Model
{
    protected $table        = 'alumnos';
    protected $primaryKey   = 'id_alumno';
    
    // CORREGIDO: Usamos el nombre real de la columna de tu BD
    protected $allowedFields = ['nombre_completo', 'dni_matricula', 'email', 'telefono', 'id_usuario', 'id_carrera', 'estado'];

    // CORRECCIÓN CRÍTICA: Desactivamos los TimeStamps porque tu tabla no los tiene
    protected $useTimestamps = false; 
    
    // Validation (Añadimos reglas y usamos el nombre correcto de la columna)
    protected $validationRules      = [
        'nombre_completo' => 'required|min_length[3]|max_length[100]',
        // La regla de unicidad apunta a la columna real 'dni_matricula'
        'dni_matricula'   => 'required|max_length[15]|is_unique[alumnos.dni_matricula,id_alumno,{id_alumno}]',
        'email'           => 'required|max_length[100]|valid_email|is_unique[alumnos.email,id_alumno,{id_alumno}]',
        'id_carrera'      => 'required|integer', 
        'telefono'        => 'permit_empty|max_length[20]',
    ];

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