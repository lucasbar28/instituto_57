<h1>Lista de Categorías</h1>

<table border="1">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $categoria): ?>
        <tr>
            <td><?= $categoria['nombre'] ?></td>
            <td><?= $categoria['descripcion'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table> 