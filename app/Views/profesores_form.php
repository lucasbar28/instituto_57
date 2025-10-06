<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Registrar Nuevo Profesor</h1>
    <p class="page-subtitle">Complete los datos personales y la especialidad del docente.</p>
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
    <form action="<?= base_url('profesores/guardar') ?>" method="post">
        <div class="form-grid">
            
            <div class="form-group">
                <label for="nombre_completo">Nombre Completo:</label>
                <input type="text" name="nombre_completo" class="form-control" value="<?= old('nombre_completo') ?>" required>
            </div>

            <div class="form-group">
                <label for="especialidad">Especialidad:</label>
                <input type="text" name="especialidad" class="form-control" value="<?= old('especialidad') ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" class="form-control" value="<?= old('telefono') ?>">
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Guardar Profesor</button>
    </form>
</div>

<?= $this->endSection() ?> 