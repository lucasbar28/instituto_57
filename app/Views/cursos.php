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

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert-danger section-box">
        <p><?= session()->getFlashdata('error') ?></p>
    </div>
<?php endif; ?>

<div class="section-box">
    <?php if (!empty($cursos) && is_array($cursos)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Nombre del Curso</th>
                    <th>Créditos</th>
                    <th>Profesor Asignado</th>
                    <th>Carrera</th>
                    <th>Cupo Máximo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $curso): ?>
                <tr>
                    <td><?= esc($curso['id_curso']) ?></td>
                    <td><?= esc($curso['codigo']) ?></td>
                    <td><?= esc($curso['nombre']) ?></td>
                    <td><?= esc($curso['creditos']) ?></td>
                    <!-- Muestra el nombre del profesor y la carrera gracias al JOIN del controlador -->
                    <td><?= esc($curso['nombre_profesor'] ?? 'N/A') ?></td> 
                    <td><?= esc($curso['nombre_carrera'] ?? 'N/A') ?></td>
                    <td><?= esc($curso['cupo_maximo']) ?></td>
                    <td>
                        <a href="<?= base_url('cursos/editar/' . $curso['id_curso']) ?>" class="btn btn-sm btn-warning">Editar</a>
                        <!-- Enlace para la Eliminación Lógica -->
                        <a href="<?= base_url('cursos/eliminar/' . $curso['id_curso']) ?>" 
                           onclick="return confirm('¿Está seguro de que desea eliminar lógicamente el curso <?= esc($curso['nombre']) ?>?')"
                           class="btn btn-sm btn-danger">Eliminar</a>
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
 