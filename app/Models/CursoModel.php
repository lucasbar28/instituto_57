<?php

namespace App\Models;

use CodeIgniter\Model;

class CursoModel extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'cursos'; 
    // Nombre de la columna que es la clave principal
    protected $primaryKey = 'id_curso'; 
    
    // Tipo de retorno
    protected $returnType = 'array';
    
    // Uso de Soft Deletes (OBLIGATORIO para usar 'deleted_at')
    protected $useSoftDeletes = true;
    
    // Nombres de las columnas de fecha/hora (opcional, pero buena práctica)
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at'; // ¡Debe coincidir con la columna de la DB!

    // Campos que están permitidos para la inserción y actualización
    protected $allowedFields = [
        'nombre', 
        'codigo', 
        'creditos', 
        'cupo_maximo', 
        'id_profesor', 
        'id_carrera', 
        'descripcion',
        // No es necesario incluir los campos de fecha aquí si useTimestamps es verdadero,
        // pero como los manejas manualmente, deben estar aquí.
        // Si no se usan timestamps automáticos, estos campos de fecha deben ser nullables en la DB.
    ];

    // Uso de TimeStamps (Debe ser true si las columnas created_at y updated_at existen y quieres que se llenen automáticamente)
    protected $useTimestamps = true; 
    
    // Si useTimestamps es true, CodeIgniter intentará llenar estos campos.
    // Si la DB los permite como NULL (como muestra tu imagen), no hay problema.
}
 