<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'usuarios';
    // CORRECCIÓN: Tu BD usa 'id_usuario'
    protected $primaryKey = 'id_usuario'; 

    protected $allowedFields = ['nombre_de_usuario', 'contrasena', 'rol', 'estado'];

    // CORRECCIÓN: Tu tabla 'usuarios' no tiene timestamps
    protected $useTimestamps = false; 
    
    protected $validationRules = [
        'contrasena'        => 'required', 
        'nombre_de_usuario' => 'required|valid_email|is_unique[usuarios.nombre_de_usuario,id_usuario,{id_usuario}]',
        
        // CORRECCIÓN: Tu ENUM de la BD usa 'administrador', no 'admin'
        'rol'               => 'required|in_list[administrador,profesor,alumno]',
        'estado'            => 'required|in_list[activo,inactivo]',
    ];
    
    protected $validationMessages = [
        'nombre_de_usuario' => [
            'is_unique' => 'Este nombre de usuario (email) ya está registrado.',
            'valid_email' => 'El campo Email debe ser una dirección de correo válida.'
        ]
    ];
} 