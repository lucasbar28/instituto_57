<?php 
    // 1. Incluir el HEAD y la etiqueta de apertura <body>
    echo view('templates/head', ['title' => 'Agregar Profesor']);
    
    // 2. Incluir el NAVBAR
    echo view('templates/navbar');
?>

<div class="container">
    <div class="page-header">
        <h1>Agregar Nuevo Profesor</h1>
    </div>

    <div class="section-box">
        <form action="<?= base_url('profesores/guardar') ?>" method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo:</label>
                    <input type="text" name="nombre_completo" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="especialidad">Especialidad:</label>
                    <input type="text" name="especialidad" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Guardar Profesor</button>
        </form>
    </div>
</div>

<?php 
    // 3. Incluir el FOOTER y las etiquetas de cierre
    echo view('templates/footer');
?>