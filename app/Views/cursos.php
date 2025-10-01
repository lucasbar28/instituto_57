<h1>Lista de Cursos</h1>

<table border="1">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Código</th>
            <th>Créditos</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cursos as $curso): ?>
        <tr>
            <td><?= $curso['nombre'] ?></td>
            <td><?= $curso['codigo'] ?></td>
            <td><?= $curso['creditos'] ?></td>
            <td><?= $curso['descripcion'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table> 