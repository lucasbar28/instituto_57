<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Categoría</title>
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1>Registrar Nueva Categoría</h1>
        <p class="page-subtitle">Define una categoría para clasificar las carreras.</p>
    </div>

    <div class="section-box">
        <form action="<?= base_url('categorias/guardar') ?>" method="post">
            <div class="form-grid">
                
                <div class="form-group">
                    <label for="nombre">Nombre de la Categoría:</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción (Opcional):</label>
                    <textarea name="descripcion" class="form-control" rows="3"></textarea>
                </div>
            
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Guardar Categoría</button>
        </form>
    </div>
</div>
</body>
</html> 