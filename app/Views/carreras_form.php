<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Registrar Nueva Carrera</h1>
    <p class="page-subtitle">Complete los detalles para agregar una nueva carrera al instituto.</p>
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
    <form action="<?= base_url('carreras/guardar') ?>" method="post">
        <div class="form-grid">
            
            <div class="form-group">
                <label for="nombre_carrera">Nombre de la Carrera:</label>
                <input type="text" name="nombre_carrera" class="form-control" value="<?= old('nombre_carrera') ?>" required>
            </div>

            <div class="form-group">
                <label for="duracion">Duración (Años):</label>
                <input type="number" name="duracion" class="form-control" value="<?= old('duracion') ?>" required min="1" max="10">
            </div>
            
            <div class="form-group">
                <label for="modalidad">Modalidad:</label>
                <select name="modalidad" class="form-control" required>
                    <option value="">Seleccione una modalidad</option>
                    <?php $selectedMod = old('modalidad'); ?>
                    <option value="Presencial" <?= $selectedMod == 'Presencial' ? 'selected' : '' ?>>Presencial</option>
                    <option value="Virtual" <?= $selectedMod == 'Virtual' ? 'selected' : '' ?>>Virtual</option>
                    <option value="Híbrida" <?= $selectedMod == 'Híbrida' ? 'selected' : '' ?>>Híbrida</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="id_categoria">Categoría:</label>
                <select name="id_categoria" class="form-control" required>
                    <option value="">Seleccione una Categoría</option>
                    <?php $selectedCat = old('id_categoria'); ?>
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= esc($categoria['id_categoria']) ?>" <?= $selectedCat == $categoria['id_categoria'] ? 'selected' : '' ?>>
                                <?= esc($categoria['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Guardar Carrera</button>
    </form>
</div>

<?= $this->endSection() ?> 