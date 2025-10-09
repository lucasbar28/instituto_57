<?php

namespace App\Models;

use CodeIgniter\Model;

class CarreraModel extends Model
{
    protected $table              = 'carreras';
    protected $primaryKey         = 'id_carrera';
    protected $useAutoIncrement   = true;
    protected $returnType         = 'array';
    
    // Dejamos $useSoftDeletes en false porque estamos manejando
    // la eliminación lógica manualmente con la columna 'estado'.
    protected $useSoftDeletes     = false; 
    
    protected $protectFields      = true;
    protected $allowedFields      = [
        'id_categoria', 
        'nombre_carrera', 
        'duracion', 
        'modalidad', 
        'estado' // La columna de eliminación lógica
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; // No usado con $useSoftDeletes = false

    // Validation
    protected $validationRules    = [
        'nombre_carrera' => 'required|min_length[3]|max_length[255]',
        'duracion'       => 'required|integer|greater_than_equal_to[1]',
        'modalidad'      => 'required|in_list[Presencial,Virtual,Mixta]',
        'id_categoria'   => 'required|integer|is_not_unique[categorias.id_categoria]',
        // Ajustamos la validación del estado para aceptar solo 0 o 1 (o nulo/vacío si lo manejas en otro lado)
        'estado'         => 'permit_empty|integer|in_list[0,1]',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setDefaultEstado']; // Asegurar que el estado sea 1 por defecto al crear
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = []; // <--- CORREGIDO: Se eliminó el callback inválido aquí.
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    /**
     * Callback para establecer el estado predeterminado (1 - Activo) si no se proporciona.
     * Esto se ejecuta ANTES de que se inserte el registro.
     * @param array $data
     * @return array
     */
    protected function setDefaultEstado(array $data)
    {
        if (!isset($data['data']['estado'])) {
            $data['data']['estado'] = 1; // 1 = Activo
        }
        return $data;
    }

    /**
     * Función para obtener solo las carreras activas (estado = 1).
     * @return array
     */
    public function findAllActive()
    {
        return $this->where('estado', 1)->findAll();
    }
    
    /**
     * Método para realizar la eliminación lógica.
     * Simplemente actualiza el campo 'estado' a 0.
     * @param int $id El ID de la carrera a "eliminar".
     * @return bool
     */
    public function softDelete($id)
    {
        return $this->update($id, ['estado' => 0]);
    }
}
 