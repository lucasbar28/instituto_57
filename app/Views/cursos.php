<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Lista de Cursos Disponibles</h1>
    <a href="<?= base_url('cursos/crear') ?>" class="btn btn-primary">Crear Nuevo Curso</a>
</div>

<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert-success section-box">
        <p><?= session()->getFlashdata('mensaje') ?></p>
    </div>
<?php endif; ?>

<div class="section-box">
    <?php if (!empty($cursos) && is_array($cursos)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nombre del Curso</th>
                    <th>Profesor Asignado</th>
                    <th>Carrera</th>
                    <th>Cupo Máximo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $curso): ?>
                <tr>
                    <td><?= esc($curso['nombre']) ?></td>
                    <td><?= esc($curso['nombre_profesor'] ?? 'N/A') ?></td> 
                    <td><?= esc($curso['nombre_carrera'] ?? 'N/A') ?></td>
                    <td><?= esc($curso['cupo_maximo']) ?></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Ver Inscritos</a>
                        <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">Aún no hay cursos registrados. Cree el primero para comenzar.</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?> 