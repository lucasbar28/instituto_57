<nav class="navbar">
    <div class="navbar-container">
        <a class="navbar-logo" href="<?= base_url() ?>">
            <img src="<?= base_url('img/logo.png') ?>" alt="Logo del Instituto" class="logo-navbar" style="height: 80px;">
        </a>
        
        <div class="navbar-links">
            
            <?php 
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
            
            // ----------------------------------------------------------------------
            // PASO 2: Enlaces de GESTIÓN (Estos se muestran SIEMPRE, ya que son el core del menú)
            // ----------------------------------------------------------------------
            ?>
            <!-- ENLACES DE GESTIÓN (Aparecen en TODAS las páginas) -->
            <a href="<?= base_url('estudiantes') ?>">Alumnos</a>
            <a href="<?= base_url('profesores') ?>">Profesores</a>
            <a href="<?= base_url('carreras') ?>">Carreras</a>
            <a href="<?= base_url('cursos') ?>">Cursos</a>
            
            <?php 
            // ----------------------------------------------------------------------
            // PASO 3: Condición para mostrar los enlaces EXTERNOS
            // Los enlaces externos se mostrarán SOLAMENTE si estamos en el controlador 'Home'.
            // ----------------------------------------------------------------------
            if ($controllerName === 'Home'): 
            ?>
                <!-- ENLACES EXTERNOS (Aparecen SOLO en la página principal/Home) -->
                <a target="_blank" href="https://abc.gob.ar/calendario_escolar">Calendario</a>
                <a target="_blank" href="https://isfdyt57-bue.infd.edu.ar/aula/acceso.cgi">CAMPUS</a>
            <?php endif; ?>
            
        </div>
    </div>
</nav>