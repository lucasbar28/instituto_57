<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Lista de Estudiantes Registrados</h1>
    <a href="<?= base_url('estudiantes/crear') ?>" class="btn btn-primary">Registrar Nuevo Alumno</a>
</div>

<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert-success section-box">
        <p><?= session()->getFlashdata('mensaje') ?></p>
    </div>
<?php endif; ?>

<div class="section-box">
    <h3 style="margin-bottom: 15px;">Listado de Alumnos</h3>
    
    <?php if (!empty($estudiantes) && is_array($estudiantes)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nombre Completo</th>
                    <th>DNI / Matrícula</th> <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $estudiante): ?>
                <tr>
                    <td><?= esc($estudiante['nombre_completo']) ?></td>
                    <td><?= esc($estudiante['dni_matricula']) ?></td> 
                    <td><?= esc($estudiante['email']) ?></td>
                    <td><?= esc($estudiante['telefono']) ?></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Editar</a>
                        <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">No hay estudiantes para mostrar. Comience a registrar alumnos.</p>
    <?php endif; ?>
</div>

<hr style="margin: 40px 0;">

<div class="section-box">
    <h2>Inscribir Estudiante a un Curso</h2>
    <p class="page-subtitle">Seleccione un alumno y un curso para registrar una nueva inscripción.</p>

    <form action="<?= base_url('inscripcion/guardar') ?>" method="post">
        <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
            
            <div class="form-group">
                <label for="id_alumno">Alumno:</label>
                <select name="id_alumno" class="form-control" required>
                    <option value="">Seleccione el Alumno</option>
                    <?php if (!empty($estudiantes) && is_array($estudiantes)): ?>
                        <?php foreach ($estudiantes as $estudiante): ?>
                            <option value="<?= esc($estudiante['id_alumno']) ?>"><?= esc($estudiante['nombre_completo']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No hay alumnos disponibles</option>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="id_curso">Curso:</label>
                <select name="id_curso" class="form-control" required>
                    <option value="">Seleccione un Curso</option>
                    <?php if (!empty($cursos) && is_array($cursos)): ?>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= esc($curso['id_curso']) ?>"><?= esc($curso['nombre_curso'] ?? $curso['nombre']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No hay cursos disponibles</option>
                    <?php endif; ?>
                </select>
            </div>

        </div>
        <button type="submit" class="btn btn-primary btn-block">Registrar Inscripción</button>
    </form>
</div>

<?= $this->endSection() ?> 