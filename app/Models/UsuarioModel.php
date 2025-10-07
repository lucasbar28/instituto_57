<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id_usuario';
    
    // El campo 'contrasena' ya no se necesita en allowedFields si usamos beforeInsert/Update
    // Pero lo mantendremos ya que se usa para la lógica de hashing.
    protected $allowedFields = ['nombre_de_usuario', 'contrasena', 'rol', 'estado'];

    // --- Callbacks de Seguridad (Hashing) ---
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    // ----------------------------------------
    
    // --- TimeStamps (Añadido para gestión automática de fechas) ---
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; 
    // -----------------------------------------------------------------

    // Función que hashea la contraseña antes de guardarla/actualizarla
    protected function hashPassword(array $data)
    {
        if (! isset($data['data']['contrasena'])) {
            return $data;
        }

        // Hashing de la contraseña
        $data['data']['contrasena'] = password_hash($data['data']['contrasena'], PASSWORD_DEFAULT);
        
        // Eliminamos el campo 'contrasena' del array $data['data'] si está vacío
        // Esto previene que se guarde un hash vacío si el usuario no ingresó nada al actualizar.
        unset($data['data']['password']); 

        return $data;
    }

    // Define el evento que se activa después de una inserción
    protected $afterInsert = ['guardarComoJSON'];

    // Esta función se ejecuta automáticamente después de insertar un usuario
    protected function guardarComoJSON(array $data)
    {
        if (isset($data['id']) && $data['id'] > 0) {
            $registro = $this->find($data['id']);

            // PRÁCTICA DE SEGURIDAD: Excluir la contraseña (aunque esté hasheada) del archivo JSON
            unset($registro['contrasena']); 

            $json_data = json_encode($registro, JSON_PRETTY_PRINT);
            
            $file_name = 'export_usuario_' . date('YmdHis') . '.json';
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