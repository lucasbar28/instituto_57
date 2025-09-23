<h1>Lista de Profesores</h1>

<table border="1">
    <thead>
        <tr>
            <th>Nombre Completo</th>
            <th>Especialidad</th>
            <th>Email</th>
            <th>Teléfono</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($profesores as $profesor): ?>
        <tr>
            <td><?= $profesor['nombre_completo'] ?></td>
            <td><?= $profesor['especialidad'] ?></td>
            <td><?= $profesor['email'] ?></td>
            <td><?= $profesor['telefono'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table> 