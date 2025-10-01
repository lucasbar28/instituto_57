<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Categorías</title>
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1>Gestión de Categorías</h1>
        <p class="page-subtitle">Las categorías se han registrado correctamente.</p>
    </div>
    
    <div class="section-box">
        <?php if (session()->getFlashdata('mensaje')): ?>
            <p style="color: green; font-weight: bold;"><?= session()->getFlashdata('mensaje') ?></p>
        <?php endif; ?>

        <a href="<?= base_url('categorias/crear') ?>" class="btn btn-primary">Registrar Otra Categoría</a>
        
        <h3 style="margin-top: 20px;">Datos en la Tabla (Prueba)</h3>
        
        <?php if (isset($categorias) && count($categorias) > 0): ?>
            <p>Se encontraron **<?= count($categorias) ?>** categorías en la base de datos.</p>
            <?php else: ?>
            <p>Aún no hay categorías registradas. Intente agregar una.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html> 