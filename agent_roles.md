## 📜 Resumen General

El sistema combina controladores, filtros y definiciones de rutas para gestionar el acceso.

1.  **Registro (`Registro.php`)**: Permite que **solo alumnos** se registren públicamente. Crea dos registros vinculados: uno en la tabla `usuarios` (para el login) y otro en la tabla `alumnos` (para los datos académicos).
2.  **Autenticación (`Login.php`)**: Valida las credenciales ( `nombre_de_usuario` y `password`) contra la tabla `usuarios`. Si tiene éxito, crea una sesión con el `rol` del usuario y un flag crítico: `cambio_obligatorio`.
3.  **Seguridad (`RoleFilter.php`)**: Es el "guardián" principal del sistema. Se aplica a casi todas las rutas protegidas. Comprueba tres cosas en orden:
    * ¿El usuario ha iniciado sesión?
    * ¿El usuario está forzado a cambiar su contraseña? (La "Jaula de Contraseña")
    * ¿El rol del usuario (`administrador`, `profesor`, `alumno`) tiene permiso para esta ruta?

---

## 1. Roles de Usuario

El sistema define tres roles principales, que se asignan en la tabla `usuarios`:

* **`administrador`**: Tiene acceso completo. Es el único rol que puede gestionar CRUDs de Carreras, Cursos, Estudiantes y Profesores, como se define en `Routes.php`. Su dashboard es `admin/dashboard`.
* **`profesor`**: Tiene acceso limitado. Puede ver la lista principal de `profesores` (probablemente su propio panel), pero las rutas de `crear`, `editar`, etc., están restringidas solo para administradores.
* **`alumno`**: Es el rol que se crea durante el registro público. Su acceso está limitado a su propio panel (probablemente `estudiantes`, aunque la gestión de este módulo la hace el admin).

---

## 2. Flujo de Registro (Solo Alumnos)

Este flujo es manejado por `Registro.php` y la vista `registro_alumno.php`.

1.  El usuario visita `GET /registro`, que muestra el formulario.
2.  El usuario envía el formulario a `POST /registro/alumno`.
3.  El controlador `Registro::registroAlumno` valida los datos. Las reglas clave son:
    * `'dni_matricula' => 'is_unique[alumnos.dni_matricula]'`
    * `'email' => 'is_unique[usuarios.email]'`
    * `'pass_confirm' => 'matches[password]'`
4.  Si la validación falla, se redirige de vuelta al formulario con errores.
5.  Si la validación es exitosa, el sistema realiza una **doble inserción**:
    * **Primero (Usuario)**: Crea el registro en `usuarios` usando `UserModel`. Se inserta el email, la contraseña *hasheada* y el rol se fija como **`'alumno'`**.
    * **Segundo (Alumno)**: Obtiene el `userId` de la inserción anterior y crea el registro en `alumnos` usando `AlumnoModel`, vinculando el `id_usuario`.
6.  **Manejo de Errores (Rollback)**: Si la creación del *alumno* falla (Paso 5.2), el sistema **elimina el usuario** creado en el Paso 5.1 para evitar registros "huérfanos" (`$userModel->delete($userId, true)`).
7.  **Éxito**: Si ambas inserciones son correctas, redirige al usuario a `/login` con un mensaje de éxito.

---

## 3. Flujo de Autenticación (Login)

Este flujo es manejado por `Login.php` y la vista `login.php`.

1.  El usuario visita `GET /login`, que muestra el formulario.
2.  El usuario envía el formulario a `POST /login`.
3.  El controlador `Login::auth` recibe los datos (`nombre_de_usuario` y `password`).
4.  Busca en `UsuarioModel` un usuario que coincida con `nombre_de_usuario`.
5.  Si lo encuentra, usa `password_verify()` para comparar la contraseña enviada con el hash de la base de datos (`$user['contrasena']`).
6.  **Si la autenticación es exitosa**:
    * Obtiene el flag `cambio_contrasena_obligatorio` de la BD y lo convierte a entero.
    * Establece los datos de la sesión:
        ```php
        $ses_data = [
            'id_usuario' => $user['id_usuario'],
            'nombre_usuario' => $user['nombre_de_usuario'], 
            'rol' => $user['rol'],
            'cambio_obligatorio' => $cambio_obligatorio, // (int) 0 o 1
            'isLoggedIn' => TRUE
        ];
        session()->set($ses_data);
        ```
       
7.  **Lógica de Redirección (¡MUY IMPORTANTE!)**:
    * **Prioridad 1 (Jaula de Contraseña)**: Si `cambio_obligatorio == 1`, el sistema **ignora el rol** y redirige forzosamente a `perfil/cambio-contrasena`.
    * **Prioridad 2 (Redirección por Rol)**: Si `cambio_obligatorio == 0`, redirige al dashboard correspondiente según el `rol`:
        * `administrador` -> `admin/dashboard`
        * `profesor` -> `profesores`
        * `alumno` -> `estudiantes`
8.  **Si la autenticación falla** (usuario no encontrado o contraseña incorrecta), se redirige de vuelta a `/login` con un mensaje de error.

---

## 4. El Sistema de Filtros y Seguridad

Esta es la parte más crítica de la seguridad. Se basa en la colaboración de 3 archivos: `Filters.php`, `Routes.php`, y `RoleFilter.php`.

### 4.1. `Config/Filters.php` (Alias)

Este archivo simplemente crea "alias" (apodos) para las clases de los filtros, para que sea más fácil usarlos en las rutas.

```php
public array $aliases = [
    // ...
    'auth'          => AuthFilter::class, //
    'role'          => RoleFilter::class, //
];
```  
auth: Es un filtro simple que solo verifica si session()->get('isLoggedIn') es TRUE. Se usa en la ruta de cambio de contraseña.

role: Es el filtro complejo que maneja roles y el cambio de contraseña obligatorio.

#### 4.2. Config/Routes.php (Aplicación) 

Este archivo *aplica* los filtros a las rutas o grupos de rutas. Aquí es donde se define **quién** puede acceder a **qué**.

* **Rutas de Admin**: Usan el filtro `role` con el argumento `administrador`.
    ```php
    // Grupo solo para administradores
    $routes->group('cursos', ['filter' => 'role:administrador'], static function ($routes) {
        $routes->get('/', 'Cursos::index');
        $routes->get('crear', 'Cursos::crear');
        // ...
    });
    ```
   
* **Rutas de Múltiples Roles**: Se pueden pasar varios roles separados por coma.
    ```php
    // Admin y Profesor pueden ver la lista
    $routes->get('/', 'Profesores::index', ['filter' => 'role:administrador,profesor']); 
    
    // Solo Admin puede crear/editar
    $routes->group('/', ['filter' => 'role:administrador'], static function ($routes) {
        $routes->get('crear', 'Profesores::crear'); 
        // ...
    });
    ```
   
* **Rutas Públicas**: Las rutas como `/login` y `/registro` no tienen ningún filtro, por lo que son accesibles para todos.

##### 4.3. App/Filters/RoleFilter.php (El "Guardián")

Este filtro es el "cerebro" de la seguridad. Se ejecuta **antes** de que el controlador de una ruta protegida sea cargado. Sigue 3 pasos de verificación:

1.  **Paso 1: ¿Está Autenticado?**
    * Comprueba `!session()->get('isLoggedIn')`.
    * Si no está logueado, redirige a `/login`.

2.  **Paso 2: ¿Cambio de Contraseña Obligatorio? (La "Jaula")**
    * Esta es la lógica más importante para la seguridad de contraseñas temporales.
    * Comprueba si `session()->get('cambio_obligatorio') == 1`.
    * Al mismo tiempo, comprueba que la ruta que el usuario intenta visitar **NO es** la página de cambio de contraseña (`perfil/cambio-contrasena`).
    * Si ambas condiciones son verdaderas (debe cambiar clave Y está intentando ir a otro sitio), lo **redirige forzosamente** a `perfil/cambio-contrasena` con una advertencia. Esto crea una "jaula" de la que el usuario no puede salir hasta que actualice su contraseña.

3.  **Paso 3: ¿Tiene el Rol Correcto?**
    * Si el usuario está logueado y no está en la "jaula de contraseña", el filtro realiza la comprobación de rol.
    * Obtiene el rol del usuario de la sesión (ej: `'profesor'`).
    * Obtiene los roles permitidos de los argumentos de la ruta (ej: `['administrador', 'profesor']`).
    * Si el rol del usuario **no está** en la lista de roles permitidos:
        * Envía un mensaje de "Acceso denegado".
        * Redirige al usuario a su *propio* dashboard (ej: `profesor` a `profesores`, `admin` a `admin/dashboard`) para evitar que quede en una página de error.