<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Lista de Profesores</h1>
    <p class="page-subtitle">Gestión de todos los docentes registrados en el instituto.</p>
    <a href="<?= base_url('profesores/crear') ?>" class="btn btn-primary">Registrar Nuevo Profesor</a>
</div>

<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert-success section-box">
        <p><?= session()->getFlashdata('mensaje') ?></p>
    </div>
<?php endif; ?>

<div class="section-box">
    <?php if (!empty($profesores) && is_array($profesores)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nombre Completo</th>
                    <th>Especialidad</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($profesores as $profesor): ?>
                <tr>
                    <td><?= esc($profesor['nombre_completo']) ?></td>
                    <td><?= esc($profesor['especialidad']) ?></td> 
                    <td><?= esc($profesor['email']) ?></td>
                    <td><?= esc($profesor['telefono']) ?></td>
                    <td>
                        <a href="<?= base_url('profesores/editar/' . $profesor['id_profesor']) ?>" class="btn btn-sm btn-info">Editar</a>
                        <a href="<?= base_url('profesores/eliminar/' . $profesor['id_profesor']) ?>" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">No hay profesores registrados para mostrar.</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?> 