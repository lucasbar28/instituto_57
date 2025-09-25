<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Estudiante</title>
</head>
<body>

    <h1>Agregar Nuevo Estudiante</h1>

    <form action="<?= base_url('estudiantes/guardar') ?>" method="post">
        <label for="nombre_completo">Nombre Completo:</label>
        <input type="text" name="nombre_completo" required><br><br>

        <label for="dni_matricula">DNI / Matrícula:</label>
        <input type="text" name="dni_matricula" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono"><br><br>

        <label for="id_carrera">Carrera:</label>
        <select name="id_carrera" required>
            <?php foreach ($carreras as $carrera): ?>
                <option value="<?= esc($carrera['id_carrera']) ?>"><?= esc($carrera['nombre_carrera']) ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <button type="submit">Guardar Estudiante</button>
    </form>
</body>
</html>  