<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Estudiantes</title>
</head>
<body>

    <h1>Lista de Estudiantes</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>DNI / Matrícula</th>
                <th>Email</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($estudiantes) && is_array($estudiantes)): ?>
                <?php foreach ($estudiantes as $estudiante): ?>
                <tr>
                    <td><?= esc($estudiante['nombre_completo']) ?></td>
                    <td><?= esc($estudiante['dni']) ?></td>
                    <td><?= esc($estudiante['email']) ?></td>
                    <td><?= esc($estudiante['telefono']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No hay estudiantes para mostrar.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <hr>

    <h2>Inscribirse a un Curso</h2>

    <form action="<?= base_url('inscripcion/guardar') ?>" method="post">
        <label for="id_alumno">Selecciona el Alumno:</label>
        <select name="id_alumno" required>
            <?php if (!empty($estudiantes) && is_array($estudiantes)): ?>
                <?php foreach ($estudiantes as $estudiante): ?>
                    <option value="<?= esc($estudiante['id_alumno']) ?>"><?= esc($estudiante['nombre_completo']) ?></option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">No hay alumnos disponibles</option>
            <?php endif; ?>
        </select>
        
        <br><br>

        <label for="id_curso">Selecciona un Curso:</label>
        <select name="id_curso" required>
            <?php if (!empty($cursos) && is_array($cursos)): ?>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?= esc($curso['id_curso']) ?>"><?= esc($curso['nombre']) ?></option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">No hay cursos disponibles</option>
            <?php endif; ?>
        </select>
        
        <br><br>

        <button type="submit">Inscribirse</button>
    </form>

</body>
</html> 