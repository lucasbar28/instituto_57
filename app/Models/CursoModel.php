<?php

namespace App\Models;

use CodeIgniter\Model;

class CursoModel extends Model
{
    protected $table = 'cursos';
    protected $primaryKey = 'id_curso';
    protected $allowedFields = ['nombre', 'codigo', 'creditos', 'descripcion', 'id_carrera'];

    protected $afterInsert = ['guardarComoJSON'];

    protected function guardarComoJSON(array $data)
    {
        if (isset($data['id']) && $data['id'] > 0) {
            $registro = $this->find($data['id']);

            $json_data = json_encode($registro, JSON_PRETTY_PRINT);
            
            $file_name = 'export_curso_' . date('YmdHis') . '.json';
            $file_path = WRITEPATH . 'exports/' . $file_name;

            if (!is_dir(WRITEPATH . 'exports')) {
                mkdir(WRITEPATH . 'exports', 0777, true);
            }

            file_put_contents($file_path, $json_data);
        }
    }
} 