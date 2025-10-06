<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Registrar Nuevo Alumno</h1>
    <p class="page-subtitle">Complete los datos personales y asigne la carrera del estudiante.</p>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert-error section-box">
        <h4>Corrija los siguientes errores:</h4>
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="section-box">
    <form action="<?= base_url('estudiantes/guardar') ?>" method="post">
        <div class="form-grid">
            
            <div class="form-group">
                <label for="nombre_completo">Nombre Completo:</label>
                <input type="text" name="nombre_completo" class="form-control" value="<?= old('nombre_completo') ?>" required>
            </div>

            <div class="form-group">
                <label for="dni_matricula">DNI / Matrícula:</label>
                <input type="text" name="dni_matricula" class="form-control" value="<?= old('dni_matricula') ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" class="form-control" value="<?= old('telefono') ?>">
            </div>

            <div class="form-group">
                <label for="id_carrera">Carrera:</label>
                <select name="id_carrera" class="form-control" required>
                    <option value="">Seleccione una Carrera</option>
                    <?php $selectedCarrera = old('id_carrera'); ?>
                    <?php if (!empty($carreras)): ?>
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?= esc($carrera['id_carrera']) ?>" 
                                <?= $selectedCarrera == $carrera['id_carrera'] ? 'selected' : '' ?>>
                                <?= esc($carrera['nombre_carrera']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Guardar Estudiante</button>
    </form>
</div>

<?= $this->endSection() ?> 