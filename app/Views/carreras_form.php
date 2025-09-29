<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nueva Carrera</title>
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1>Registrar Nueva Carrera</h1>
        <p class="page-subtitle">Complete los detalles para agregar una nueva carrera al instituto.</p>
    </div>

    <div class="section-box">
        <form action="<?= base_url('carreras/guardar') ?>" method="post">
            <div class="form-grid">
                
                <div class="form-group">
                    <label for="nombre_carrera">Nombre de la Carrera:</label>
                    <input type="text" name="nombre_carrera" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="duracion">Duración (Años):</label>
                    <input type="number" name="duracion" class="form-control" required min="1" max="10">
                </div>
                
                <div class="form-group">
                    <label for="modalidad">Modalidad:</label>
                    <select name="modalidad" class="form-control" required>
                        <option value="">Seleccione una modalidad</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Virtual">Virtual</option>
                        <option value="Híbrida">Híbrida</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="id_categoria">Categoría:</label>
                    <select name="id_categoria" class="form-control" required>
                        <option value="">Seleccione una Categoría</option>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= esc($categoria['id_categoria']) ?>"><?= esc($categoria['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Guardar Carrera</button>
        </form>
    </div>
</div>
</body>
</html> 