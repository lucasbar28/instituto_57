<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Carreras</title>
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1>Lista de Carreras Registradas</h1>
        <p class="page-subtitle">Aquí verás los datos de las carreras insertadas.</p>
    </div>
    
    <div class="section-box">
        <a href="<?= base_url('carreras/crear') ?>" class="btn btn-primary">Registrar Nueva Carrera</a>
        
        <p style="margin-top: 20px;">**¡Éxito! La carrera se guardó y la redirección funcionó.**</p>
        
        <?php if (isset($carreras) && count($carreras) > 0): ?>
            <?php else: ?>
            <p class="text-muted">Aún no hay carreras registradas en el sistema.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html> 