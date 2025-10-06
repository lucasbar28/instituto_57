<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Lista de Carreras Registradas</h1>
    <p class="page-subtitle">Aquí verás los datos de las carreras insertadas.</p>
</div>

<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert-success section-box">
        <p><?= session()->getFlashdata('mensaje') ?></p>
    </div>
<?php endif; ?>

<div class="section-box">
    <a href="<?= base_url('carreras/crear') ?>" class="btn btn-primary">Registrar Nueva Carrera</a>
    
    <div style="margin-top: 20px;">
        <?php if (isset($carreras) && count($carreras) > 0): ?>
            <p>Se encontraron **<?= count($carreras) ?>** carreras registradas.</p>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Carrera</th>
                        <th>Duración (Años)</th>
                        <th>Modalidad</th>
                        <th>ID Categoría</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carreras as $carrera): ?>
                        <tr>
                            <td><?= esc($carrera['id_carrera']) ?></td>
                            <td><?= esc($carrera['nombre_carrera']) ?></td>
                            <td><?= esc($carrera['duracion']) ?></td>
                            <td><?= esc($carrera['modalidad']) ?></td>
                            <td><?= esc($carrera['id_categoria']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>
            <p class="text-muted">Aún no hay carreras registradas en el sistema.</p>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?> 