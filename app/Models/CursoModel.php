<?php

namespace App\Models;

use CodeIgniter\Model;

class CursoModel extends Model
{
    protected $table      = 'cursos';
    protected $primaryKey = 'id_curso';
    
    // CORRECCIÓN: Añadidos todos los campos de tu BD
    protected $allowedFields = ['nombre', 'codigo', 'creditos', 'descripcion', 'id_carrera', 'id_profesor', 'cupo_maximo', 'anio'];

    // --- TimeStamps (CORRECTO, tu BD los tiene) ---
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; 
    protected $useSoftDeletes = true;
    // --------------------------------

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
        
        // CORRECCIÓN: Faltaba el return
        return $data;
    }
    
    /**
     * FUNCIÓN AÑADIDA: Requerida por Cursos.php para el listado
     * Obtiene los cursos con el nombre de la carrera y profesor.
     */
    public function findAllWithRelations()
    {
        // El findAll() aplica automáticamente el filtro de Soft Delete (deleted_at IS NULL)
        return $this->select('cursos.*, c.nombre_carrera, p.nombre_completo as nombre_profesor')
                    ->join('carreras c', 'c.id_carrera = cursos.id_carrera', 'left')
                    ->join('profesores p', 'p.id_profesor = cursos.id_profesor', 'left')
                    ->findAll(); 
    }
} 