<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios'; 
    protected $primaryKey = 'id_usuario';
    
    protected $returnType = 'array';
    protected $useTimestamps = false; 
    protected $useAutoIncrement = true;
    
    // Campos permitidos, ahora incluyendo el campo de seguridad
    protected $allowedFields = [
        'nombre_de_usuario',
        'contrasena',
        'rol', 
        'estado',
        'cambio_contrasena_obligatorio'
    ];

    // Callbacks para hashear la contraseña
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hashea la contraseña antes de guardarla en la base de datos.
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['contrasena'])) {
            $data['data']['contrasena'] = password_hash($data['data']['contrasena'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}
