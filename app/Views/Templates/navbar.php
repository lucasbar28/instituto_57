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
            <?php            if (in_array($controllerName, ['Carreras', 'Cursos'])): ?>
                <a href="<?= base_url(strtolower($controllerName)) ?>" class="active"><?= $controllerName ?></a>
                <a href="javascript:history.back()" class="back-link">Volver</a>
            <?php else: ?>
                <?php if ($controllerName !== 'Profesores'): ?>
                    <a href="<?= base_url('estudiantes') ?>">Alumnos</a>
                <?php endif; ?>

                <?php if ($controllerName !== 'Estudiantes'): ?>
                    <a href="<?= base_url('profesores') ?>">Profesores</a>
                <?php endif; ?>

                <?php if ($controllerName !== 'Estudiantes'): ?>
                    <a href="<?= base_url('carreras') ?>">Carreras</a>
                <?php endif; ?>

                <?php if ($controllerName !== 'Profesores'): ?>
                    <a href="<?= base_url('cursos') ?>">Cursos</a>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php 
            // ----------------------------------------------------------------------
            // PASO 3: Condición para mostrar los enlaces EXTERNOS
            // Los enlaces externos se mostrarán SOLAMENTE si estamos en el controlador 'Home'.
            // ----------------------------------------------------------------------
            if ($controllerName === 'Home' || $controllerName === 'Estudiantes'): 
            ?>
            <!-- ENLACES EXTERNOS (Aparecen en Home y en Estudiantes) -->
            <a target="_blank" href="https://abc.gob.ar/calendario_escolar">Calendario</a>
            <a target="_blank" href="https://isfdyt57-bue.infd.edu.ar/aula/acceso.cgi">Campus</a>
            <a target="_blank" href="https://isfdyt57.sambaweb.com.ar/">Samba</a>
            <?php endif; ?>
            
        </div>
    </div>
</nav>