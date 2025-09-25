<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Estudiante</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1>Agregar Nuevo Estudiante</h1>
    </div>

    <div class="section-box">
        <form action="<?= base_url('estudiantes/guardar') ?>" method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo:</label>
                    <input type="text" name="nombre_completo" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="dni_matricula">DNI / Matrícula:</label>
                    <input type="text" name="dni_matricula" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" class="form-control">
                </div>

                <div class="form-group">
                    <label for="id_carrera">Carrera:</label>
                    <select name="id_carrera" class="form-control" required>
                        <?php if (!empty($carreras)): ?>
                            <?php foreach ($carreras as $carrera): ?>
                                <option value="<?= esc($carrera['id_carrera']) ?>"><?= esc($carrera['nombre_carrera']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Guardar Estudiante</button>
        </form>
    </div>
</div>
</body>
</html> 