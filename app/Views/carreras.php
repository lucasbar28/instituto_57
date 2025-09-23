<h1>Lista de Carreras</h1>

<table border="1">
    <thead>
        <tr>
            <th>Nombre de la Carrera</th>
            <th>Duración</th>
            <th>Modalidad</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($carreras as $carrera): ?>
        <tr>
            <td><?= $carrera['nombre_carrera'] ?></td>
            <td><?= $carrera['duracion'] ?></td>
            <td><?= $carrera['modalidad'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table> 