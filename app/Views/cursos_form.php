<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Registrar Nuevo Curso</h1>
    <a href="<?= base_url('cursos') ?>" class="btn btn-secondary">Volver al Listado de Cursos</a>
</div>

<div class="section-box">
    <p class="page-subtitle">Complete los campos para añadir un nuevo curso al catálogo.</p>
    
    <!-- Muestra errores de sesión (errores de validación) -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-danger section-box">
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>
    
    <!-- Muestra la lista de errores si existen -->
    <?php $validation = \Config\Services::validation(); ?>
    <?php if ($validation->getErrors()): ?>
        <div class="alert-danger section-box">
            <p><strong>🚨 Error en el formulario:</strong> Por favor, revise los siguientes errores:</p>
            <ul>
                <?php foreach ($validation->getErrors() as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('cursos/guardar') ?>" method="post">
        
        <div class="form-grid">
            
            <!-- Nombre del Curso -->
            <div class="form-group">
                <label for="nombre_curso">Nombre del Curso:</label>
                <input type="text" name="nombre_curso" id="nombre_curso" class="form-control <?= $validation->hasError('nombre_curso') ? 'is-invalid' : '' ?>" 
                       value="<?= old('nombre_curso') ?>" required>
                <?php if ($validation->hasError('nombre_curso')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('nombre_curso') ?></div>
                <?php endif; ?>
            </div>

            <!-- Código del Curso -->
            <div class="form-group">
                <label for="codigo_curso">Código del Curso (Ej: INF101):</label>
                <input type="text" name="codigo_curso" id="codigo_curso" class="form-control <?= $validation->hasError('codigo_curso') ? 'is-invalid' : '' ?>" 
                       value="<?= old('codigo_curso') ?>" required>
                <?php if ($validation->hasError('codigo_curso')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('codigo_curso') ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Profesor Asignado (Dropdown) -->
            <div class="form-group">
                <label for="id_profesor">Profesor Asignado:</label>
                <select name="id_profesor" id="id_profesor" class="form-control <?= $validation->hasError('id_profesor') ? 'is-invalid' : '' ?>" required>
                    <option value="">Seleccione un Profesor</option>
                    <?php if (!empty($profesores)): ?>
                        <?php foreach ($profesores as $profesor): ?>
                            <option value="<?= esc($profesor['id_profesor']) ?>" 
                                <?= old('id_profesor') == $profesor['id_profesor'] ? 'selected' : '' ?>>
                                <?= esc($profesor['nombre_completo']) ?> (<?= esc($profesor['especialidad']) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No hay profesores disponibles</option>
                    <?php endif; ?>
                </select>
                <?php if ($validation->hasError('id_profesor')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('id_profesor') ?></div>
                <?php endif; ?>
            </div>

            <!-- Carrera Asociada (Dropdown) -->
            <div class="form-group">
                <label for="id_carrera">Carrera Asociada:</label>
                <select name="id_carrera" id="id_carrera" class="form-control <?= $validation->hasError('id_carrera') ? 'is-invalid' : '' ?>" required>
                    <option value="">Seleccione una Carrera</option>
                    <?php if (!empty($carreras)): ?>
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?= esc($carrera['id_carrera']) ?>"
                                <?= old('id_carrera') == $carrera['id_carrera'] ? 'selected' : '' ?>>
                                <?= esc($carrera['nombre_carrera']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No hay carreras disponibles</option>
                    <?php endif; ?>
                </select>
                <?php if ($validation->hasError('id_carrera')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('id_carrera') ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Botón de Guardar -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Guardar Curso</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
