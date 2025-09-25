<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Profesor</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

    <h1>Agregar Nuevo Profesor</h1>

    <form action="<?= base_url('profesores/guardar') ?>" method="post">
        <label for="nombre_completo">Nombre Completo:</label>
        <input type="text" name="nombre_completo" required><br><br>

        <label for="especialidad">Especialidad:</label>
        <input type="text" name="especialidad" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono"><br><br>

        <button type="submit">Guardar Profesor</button>
    </form>
</body>
</html> 