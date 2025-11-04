<?php
// Obtener la instancia de la sesión (CodeIgniter 4)
$session = \Config\Services::session();
$baseURL = base_url(); 

// ----------------------------------------------------------------------
// PASO 1: Obtener el nombre del controlador actual.
// ----------------------------------------------------------------------
try {
    // Intenta obtener el nombre completo del controlador (ej: \App\Controllers\Home)
    $currentController = service('router')->controllerName();
    // Limpiamos el string para obtener solo el nombre (ej: Home)
    $controllerName = basename(str_replace('\\', '/', $currentController));
} catch (\Exception $e) {
    // Fallback en caso de error 
    $controllerName = ''; 
}
?>

<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <a class="navbar-logo" href="<?= $baseURL ?>">
            <img src="<?= $baseURL . 'img/logo.png' ?>" alt="Logo del Instituto" class="logo-navbar" style="height: 80px;">
        </a>
        
        <div class="navbar-links">
            
            <!-- SECCIÓN DE ENLACES DE GESTIÓN -->
            <?php 
            // ----------------------------------------------------------------------
            // PASO 2: Enlaces de GESTIÓN 
            // (La línea siguiente estaba causando el error por formato/espacios)
            // ----------------------------------------------------------------------
            if (in_array($controllerName, ['Carreras', 'Cursos'])): 
            ?>
                <!-- Se muestran los enlaces de la sección activa y Volver -->
                <a href="<?= $baseURL . strtolower($controllerName) ?>" class="active"><?= $controllerName ?></a>
                <a href="javascript:history.back()" class="back-link">Volver</a>
            <?php else: ?>
                <!-- Se muestran los enlaces estándar (se oculta el de la página actual) -->
                <?php if ($controllerName !== 'Profesores'): ?>
                    <a href="<?= $baseURL . 'estudiantes' ?>">Alumnos</a>
                <?php endif; ?>

                <?php if ($controllerName !== 'Estudiantes'): ?>
                    <a href="<?= $baseURL . 'profesores' ?>">Profesores</a>
                <?php endif; ?>

                <?php if ($controllerName !== 'Estudiantes'): ?>
                    <a href="<?= $baseURL . 'carreras' ?>">Carreras</a>
                <?php endif; ?>

                <?php if ($controllerName !== 'Profesores'): ?>
                    <a href="<?= $baseURL . 'cursos' ?>">Cursos</a>
                <?php endif; ?>
            <?php endif; ?>
            
            <!-- SECCIÓN DE ENLACES EXTERNOS -->
            <?php 
            // ----------------------------------------------------------------------
            // PASO 3: Condición para mostrar los enlaces EXTERNOS
            // ----------------------------------------------------------------------
            if ($controllerName === 'Home' || $controllerName === 'Estudiantes'): 
            ?>
            <a target="_blank" href="https://abc.gob.ar/calendario_escolar">Calendario</a>
            <a target="_blank" href="https://isfdyt57-bue.infd.edu.ar/aula/acceso.cgi">Campus</a>
            <a target="_blank" href="https://isfdyt57.sambaweb.com.ar/">Samba</a>
            <?php endif; ?>
            
            <!-- *************************************************************** -->
            <!-- LÓGICA DE SESIÓN: Cerrar Sesión o Iniciar Sesión/Registrarse   -->
            <!-- *************************************************************** -->
            <?php if ($session->get('isLoggedIn')): ?>
                
                <!-- Contenedor del Perfil y Cerrar Sesión -->
                <div class="user-profile-links">
                    
                    <!-- Información de Usuario -->
                    <span class="user-info">
                        ¡Hola, **<?= esc($session->get('nombre_usuario')) ?>**!
                        <small class="user-role"><?= esc(ucfirst($session->get('rol'))) ?></small>
                    </span>

                    <!-- Botón de Cambio de Contraseña (si es obligatorio) -->
                    <?php if ($session->get('cambio_obligatorio') == 1): ?>
                        <a href="<?= $baseURL . 'perfil/cambio-contrasena' ?>" 
                           class="btn-password-required">
                            ¡CAMBIAR CONTRASEÑA OBLIGATORIO!
                        </a>
                    <?php endif; ?>
                    
                    <!-- ENLACE DE CERRAR SESIÓN -->
                    <a href="<?= $baseURL . 'logout' ?>" 
                       class="btn-logout">
                        Cerrar Sesión
                    </a>
                </div>
                
            <?php else: ?>
                <!-- Enlaces para usuarios NO logueados -->
                <a href="<?= $baseURL . 'login' ?>" class="btn-login">
                    Iniciar Sesión
                </a>
                <a href="<?= $baseURL . 'registro' ?>" class="btn-register">
                    Registrarse
                </a>
            <?php endif; ?>
            <!-- *************************************************************** -->

        </div>
    </div>
</nav>
 