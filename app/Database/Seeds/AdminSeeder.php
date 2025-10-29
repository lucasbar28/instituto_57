<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $usuarioModel = new \App\Models\UsuarioModel();

        // Contraseña: 'admin123' (se hashea automáticamente)
        $password_hash = password_hash('admin123', PASSWORD_DEFAULT); 
        
        $data = [
            'nombre_de_usuario' => 'admin@instituto.com',
            'contrasena'        => $password_hash,
            'rol'               => 'administrador', // El rol clave
            'estado'            => 'activo',
        ];

        // Usamos el método insert() del modelo para guardar el usuario
        if ($usuarioModel->insert($data) === false) {
             // Esto imprimirá los errores de validación en la consola
             echo "Error al insertar el usuario admin:\n";
             print_r($usuarioModel->errors());
        } else {
            echo "✅ Usuario administrador creado exitosamente.\n";
        }
    }
}
 