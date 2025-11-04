<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CarreraModel;
use App\Models\UserModel;
use App\Models\AlumnoModel;

class Registro extends BaseController
{
    /**
     * Muestra el formulario de registro para un nuevo alumno.
     */
    public function index()
    {
        // Redirigir si ya está logueado
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'))->with('info', 'Ya estás autenticado.');
        }

        $carreraModel = new CarreraModel();
        $data = [
            'carreras' => $carreraModel->findAll(),
            'title' => 'Registro de Alumno'
        ];
        
        // Carga la vista de registro. Nota: 'auth/registro_alumno'
        return view('auth/registro_alumno', $data);
    }

    /**
     * Procesa el registro de un nuevo alumno.
     * Crea un usuario y luego el registro de alumno asociado.
     */
    public function registroAlumno()
    {
        // Reglas de validación
        $rules = [
            'dni_matricula' => 'required|max_length[20]|is_unique[alumnos.dni_matricula]',
            'nombre_completo' => 'required|max_length[255]',
            'email' => 'required|max_length[255]|valid_email|is_unique[usuarios.email]',
            'password' => 'required|min_length[6]',
            'pass_confirm' => 'required_with[password]|matches[password]',
            'id_carrera' => 'required|is_natural_no_zero',
        ];

        if (! $this->validate($rules)) {
            // Si la validación falla, regresa al formulario con los errores y datos antiguos
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost();

        // 1. CREACIÓN DEL USUARIO
        $userModel = new UserModel();
        $userId = $userModel->insert([
            'email' => $post['email'],
            'password' => password_hash($post['password'], PASSWORD_DEFAULT),
            'rol' => 'alumno', // Rol por defecto
            'activo' => 1 // Usuario activo al registrarse
        ]);

        if (! $userId) {
            // Manejar error en creación de usuario
             return redirect()->back()->withInput()->with('error', 'Error al crear el usuario. Inténtelo de nuevo.');
        }

        // 2. CREACIÓN DEL REGISTRO DE ALUMNO
        $alumnoModel = new AlumnoModel();
        $alumnoData = [
            'id_usuario' => $userId,
            'id_carrera' => $post['id_carrera'],
            'dni_matricula' => $post['dni_matricula'],
            'nombre_completo' => $post['nombre_completo'],
            'email' => $post['email'], // Almacenamos el email en la tabla de alumnos también
        ];

        if (! $alumnoModel->insert($alumnoData)) {
            // Si falla la creación del alumno, se debe eliminar el usuario para evitar inconsistencias
            $userModel->delete($userId, true); 
            return redirect()->back()->withInput()->with('error', 'Error al crear el registro de alumno. Por favor, contacte a soporte.');
        }

        // 3. REGISTRO EXITOSO: Redirigir al Login
        return redirect()->to(base_url('login'))->with('success', '¡Registro exitoso! Ya puedes iniciar sesión.');
    }
}
