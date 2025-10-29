<?php 
/**
 * Vista: Lista de Cursos con opciones de CRUD.
 * Estilo actualizado para coincidir con la vista de Carreras (Título, Alertas y Botones).
 */
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="container mt-5">
    
    <!-- 1. ALERTAS (Usando la vista unificada de templates) -->
    <?= view('templates/_alerts') ?>

    <!-- 2. TÍTULO Y BOTÓN DE CREACIÓN (Usando estilos consistentes) -->
    <div class="d-flex flex-column align-items-center justify-content-center mb-5">
        <!-- Título principal -->
        <h1 class="display-5 fw-bold text-primary mb-3">Lista de Cursos Disponibles</h1>
        
        <!-- Botón de Creación -->
        <a href="<?= base_url('cursos/crear') ?>" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus-circle"></i> Crear Nuevo Curso
        </a>
    </div>

    <!-- 3. CONTENEDOR DE LA TABLA -->
    <div class="table-responsive">
        <?php if (!empty($cursos) && is_array($cursos)): ?>
            <table class="data-table">
                
                <!-- ENCABEZADO -->
                <thead>
                    <tr>
                        <th class="col-1">ID</th>
                        <th class="col-1">Código</th>
                        <th class="col-3">Nombre del Curso</th>
                        <th class="col-1 text-center">Créditos</th>
                        <th class="col-2">Profesor Asignado</th>
                        <th class="col-2">Carrera</th>
                        <th class="col-1 text-center">Cupo</th>
                        <th class="col-2 text-center">Acciones</th>
                    </tr>
                </thead>
                
                <!-- CUERPO DE LA TABLA -->
                <tbody>
                    <?php foreach ($cursos as $curso): ?>
                    <tr>
                        <td class="align-middle"><?= esc($curso['id_curso']) ?></td>
                        <td class="align-middle"><?= esc($curso['codigo']) ?></td>
                        <td class="align-middle"><?= esc($curso['nombre']) ?></td>
                        <td class="align-middle text-center"><?= esc($curso['creditos']) ?></td>
                        <!-- Muestra el nombre del profesor y la carrera gracias al JOIN del controlador -->
                        <td class="align-middle"><?= esc($curso['nombre_profesor'] ?? 'N/A') ?></td> 
                        <td class="align-middle"><?= esc($curso['nombre_carrera'] ?? 'N/A') ?></td>
                        <td class="align-middle text-center"><?= esc($curso['cupo_maximo']) ?></td>
                        
                        <!-- ACCIONES: USANDO CLASES PERSONALIZADAS (btn-action, btn-edit, btn-delete) -->
                        <td class="align-middle action-buttons">
                            <!-- Botón Editar -->
                            <a href="<?= base_url('cursos/editar/' . $curso['id_curso']) ?>" class="btn-action btn-edit" title="Editar Curso">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <!-- Botón Eliminar (Eliminación Lógica) -->
                            <a href="<?= base_url('cursos/eliminar/' . $curso['id_curso']) ?>" 
                               onclick="return confirm('¿Está seguro de que desea eliminar lógicamente el curso <?= esc($curso['nombre']) ?>?')"
                               class="btn-action btn-delete" title="Desactivar Curso">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="text-center py-5">
                <p class="text-muted lead">Aún no hay cursos registrados. Cree el primero para comenzar.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>
 