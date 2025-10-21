<?php 
    // 1. Incluye el inicio del HTML, el <head>, y la etiqueta <body>
    // El contenido de head.php termina con la etiqueta <body> abierta.
    echo view('templates/head'); 
?>

<?php 
    // 2. Incluye la barra de navegación
    echo view('templates/navbar'); 
?>

<main class="container">
    <?= $this->renderSection('content') ?> 
</main>

<?php 
    // 4. Incluye el pie de página y cierra el HTML
    // El contenido de footer.php debe cerrar </body> y </html>.
    echo view('templates/footer'); 
?> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script src="<?= base_url('js/app.js') ?>"></script> 
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
</body>