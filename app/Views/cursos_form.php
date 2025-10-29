<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<?php 
    // Determinar si estamos creando o editando
    $is_editing = isset($curso) && $curso !== null;
    $title = $is_editing ? 'Editar Curso: ' . esc($curso['nombre']) : 'Registrar Nuevo Curso';
    $action_url = base_url($is_editing ? 'cursos/actualizar' : 'cursos/guardar');

    // Inicializar la validación
    $validation = \Config\Services::validation(); 
?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="<?= base_url('cursos') ?>" class="btn btn-secondary">Volver al Listado de Cursos</a>
</div>

<div class="section-box">
    <p class="page-subtitle"><?= $is_editing ? 'Modifique los campos necesarios y guarde los cambios.' : 'Complete los campos para añadir un nuevo curso al catálogo.' ?></p>
    
    <!-- Muestra errores de sesión (errores de validación) -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-danger section-box">
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>
    
    <!-- Muestra la lista de errores si existen -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert-danger section-box">
            <p><strong>🚨 Error en el formulario:</strong> Por favor, revise los siguientes errores:</p>
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= $action_url ?>" method="post">
        <?= csrf_field() ?>
        
        <?php if ($is_editing): ?>
            <!-- Campo Oculto para el ID en Edición -->
            <input type="hidden" name="id_curso" value="<?= esc($curso['id_curso']) ?>">
        <?php endif; ?>

        <div class="form-grid-3">
            
            <!-- Nombre del Curso -->
            <div class="form-group">
                <label for="nombre">Nombre del Curso:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" 
                        value="<?= old('nombre', $is_editing ? $curso['nombre'] : '') ?>" required>
                <?php if ($validation->hasError('nombre')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('nombre') ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Código del Curso -->
            <div class="form-group">
                <label for="codigo">Código del Curso (Ej: TSCD-LC-101):</label>
                <input type="text" name="codigo" id="codigo" class="form-control" 
                        value="<?= old('codigo', $is_editing ? $curso['codigo'] : '') ?>" required>
                <?php if ($validation->hasError('codigo')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('codigo') ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Créditos -->
            <div class="form-group">
                <label for="creditos">Créditos:</label>
                <input type="number" name="creditos" id="creditos" class="form-control" 
                        value="<?= old('creditos', $is_editing ? $curso['creditos'] : '') ?>" required min="1">
                <?php if ($validation->hasError('creditos')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('creditos') ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Cupo Máximo -->
            <div class="form-group">
                <label for="cupo_maximo">Cupo Máximo:</label>
                <input type="number" name="cupo_maximo" id="cupo_maximo" class="form-control" 
                        value="<?= old('cupo_maximo', $is_editing ? $curso['cupo_maximo'] : '') ?>" required min="1">
                <?php if ($validation->hasError('cupo_maximo')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('cupo_maximo') ?></div>
                <?php endif; ?>
            </div>

            <!-- Profesor Asignado (Dropdown) -->
            <div class="form-group">
                <label for="id_profesor">Profesor Asignado:</label>
                <select name="id_profesor" id="id_profesor" class="form-control" required>
                    <option value="">Seleccione un Profesor</option>
                    <?php 
                    $selected_profesor = old('id_profesor', $is_editing ? $curso['id_profesor'] : '');
                    if (!empty($profesores)): ?>
                        <?php foreach ($profesores as $profesor): ?>
                            <option value="<?= esc($profesor['id_profesor']) ?>" 
                                <?= $selected_profesor == $profesor['id_profesor'] ? 'selected' : '' ?>>
                                <?= esc($profesor['nombre_completo']) ?>
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
                <select name="id_carrera" id="id_carrera" class="form-control" required>
                    <option value="">Seleccione una Carrera</option>
                    <?php 
                    $selected_carrera = old('id_carrera', $is_editing ? $curso['id_carrera'] : '');
                    if (!empty($carreras)): ?>
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?= esc($carrera['id_carrera']) ?>"
                                <?= $selected_carrera == $carrera['id_carrera'] ? 'selected' : '' ?>>
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
        
        <!-- Descripción (Campo completo) -->
        <div class="form-group-full">
            <label for="descripcion">Descripción del Curso (Opcional):</label>
            <textarea name="descripcion" id="descripcion" rows="4" class="form-control"><?= old('descripcion', $is_editing ? $curso['descripcion'] : '') ?></textarea>
            <?php if ($validation->hasError('descripcion')): ?>
                <div class="invalid-feedback"><?= $validation->getError('descripcion') ?></div>
            <?php endif; ?>
        </div>
        
        <!-- Botón de Guardar -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">
                <?= $is_editing ? 'Guardar Cambios' : 'Guardar Curso' ?>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
 