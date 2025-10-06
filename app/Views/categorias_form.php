<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Registrar Nueva Categoría</h1>
    <p class="page-subtitle">Define una categoría para clasificar las carreras.</p>
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
    <form action="<?= base_url('categorias/guardar') ?>" method="post">
        <div class="form-grid">
            
            <div class="form-group">
                <label for="nombre">Nombre de la Categoría:</label>
                <input type="text" name="nombre" class="form-control" value="<?= old('nombre') ?>" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción (Opcional):</label>
                <textarea name="descripcion" class="form-control" rows="3"><?= old('descripcion') ?></textarea>
            </div>
        
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Guardar Categoría</button>
    </form>
</div>

<?= $this->endSection() ?> 