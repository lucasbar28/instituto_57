<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id'; 

    protected $allowedFields = ['nombre_de_usuario', 'contrasena', 'rol', 'estado'];

    // Se mantiene en false para evitar el error de created_at/updated_at
    protected $useTimestamps = false; 
    
    protected $validationRules = [
        // Aceptamos cualquier largo, ya que es el hash que se va a guardar
        'contrasena'        => 'required', 
        
        // El email es el nombre de usuario y debe ser único.
        'nombre_de_usuario' => 'required|valid_email|is_unique[usuarios.nombre_de_usuario]',
        
        'rol'               => 'required|in_list[admin,profesor,alumno]',
        'estado'            => 'required|in_list[activo,inactivo]',
    ];
    
    protected $validationMessages = [
        'nombre_de_usuario' => [
            'is_unique' => 'Este nombre de usuario (email) ya está registrado.',
            'valid_email' => 'El campo Email debe ser una dirección de correo válida.'
        ]
    ];
}
 