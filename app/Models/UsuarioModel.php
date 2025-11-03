<?php namespace App\Models;

use CodeIgniter\Model;

// El nombre de la clase debe ser 'UsuarioModel' si el archivo se llama UsuarioModel.php
class UsuarioModel extends Model
{
    // Nombre de la tabla de usuarios en tu base de datos (según tus capturas, es 'usuarios')
    protected $table = 'usuarios'; 
    
    // Nombre de la clave primaria
    protected $primaryKey = 'id_usuario';
    
    // Tipo de retorno que deseas (array, object, etc.)
    protected $returnType = 'array';
    
    // Indica si se usan marcas de tiempo (created_at, updated_at)
    protected $useTimestamps = false; 

    // Campos permitidos para la inserción y actualización (MANDATORIO por seguridad)
    // Asegúrate de que estos nombres coincidan con las columnas de tu tabla 'usuarios'
    // IMPORTANTE: Si usas 'nombre_de_usuario' en la BD, debes incluirlo aquí.
    protected $allowedFields = [
        'nombre_de_usuario', // <-- Añadido/Corregido según tu BD (image_4a78b8.png)
        'email', 
        'contrasena', // <-- Corregido a 'contrasena' según tu BD (image_4a78b8.png)
        'password',   // Si lo usas temporalmente para el registro
        'rol',        
        'estado'      
    ];

    /*
     * Funciones opcionales para usar antes o después de la inserción/actualización
     * protected $beforeInsert = ['hashPassword'];
     * protected $beforeUpdate = ['hashPassword'];
    */
    
    // NOTA IMPORTANTE: En tu tabla de BD (image_4a78b8.png), los campos son:
    // id_usuario, nombre_de_usuario, contrasena, rol, estado. 
    // He ajustado allowedFields arriba para reflejar esto mejor.
}
 