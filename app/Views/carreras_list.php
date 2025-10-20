<?php 
/**
 * Vista: Lista de Carreras con opciones de CRUD.
 * Extiende el layout principal 'templates/layout'.
 * * CORRECCIÓN: Se ajusta la lógica para mostrar el estado de la carrera
 * como "Activa" o "Inactiva" en lugar del valor numérico (1 o 0).
 */
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="container mt-5">
    
    <!-- 1. ALERTAS (Mensajes de éxito/error) -->
    <?= view('templates/_alerts') ?>

    <!-- 2. TÍTULO Y BOTÓN DE CREACIÓN (Usando estilos consistentes) -->
    <div class="d-flex flex-column align-items-center justify-content-center mb-5">
        <!-- Título principal -->
        <h1 class="display-5 fw-bold text-primary mb-3">Lista de Carreras (Activas)</h1>
        
        <!-- Botón de Creación -->
        <a href="<?= base_url('carreras/crear') ?>" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus-circle"></i> Registrar Nueva Carrera
        </a>
    </div>

    <!-- 3. CONTENEDOR DE LA TABLA -->
    <div class="table-responsive">
        <table class="data-table"> 
            
            <!-- ENCABEZADO -->
            <thead> 
                <tr>
                    <th class="col-1">ID</th>
                    <th class="col-4">Nombre de Carrera</th>
                    <th class="col-1 text-center">Duración (Años)</th>
                    <th class="col-2 text-center">Modalidad</th>
                    <th class="col-1 text-center">Estado</th>
                    <th class="col-2 text-center">Acciones</th>
                </tr>
            </thead>
            
            <!-- CUERPO DE LA TABLA -->
            <tbody>
                <?php if (empty($carreras)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">No hay carreras activas registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($carreras as $carrera): ?>
                    <tr>
                        <!-- DATOS DE LA CARRERA -->
                        <td class="align-middle"><?= esc($carrera['id_carrera']) ?></td>
                        <td class="align-middle"><?= esc($carrera['nombre_carrera']) ?></td> 
                        <td class="align-middle text-center"><?= esc($carrera['duracion'] ?? 'N/A') ?></td>
                        <td class="align-middle text-center"><?= esc($carrera['modalidad'] ?? 'N/A') ?></td>
                        
                        <!-- ESTADO (Badge) - LÓGICA CORREGIDA -->
                        <td class="align-middle text-center">
                            <?php 
                                // Determinar la clase de Bootstrap y el texto a mostrar basado en el valor 1 o 0
                                $is_active = isset($carrera['estado']) && $carrera['estado'] == 1;
                                $estado_class = $is_active ? 'bg-success' : 'bg-danger'; 
                                $estado_text = $is_active ? 'Activa' : 'Inactiva';
                            ?>
                            <span class="badge <?= $estado_class ?> text-white p-2 rounded-pill">
                                <?= esc($estado_text) ?>
                            </span>
                        </td>
                        
                        <!-- ACCIONES: USANDO CLASES PERSONALIZADAS (btn-action, btn-edit, btn-delete) -->
                        <td class="align-middle action-buttons"> 
                            <!-- Botón Editar -->
                            <a href="<?= base_url("carreras/editar/{$carrera['id_carrera']}") ?>" class="btn-action btn-edit" title="Editar Carrera">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <!-- Botón Eliminar (Eliminación Lógica) -->
                            <a href="<?= base_url("carreras/eliminar/{$carrera['id_carrera']}") ?>" class="btn-action btn-delete" title="Eliminar Carrera" onclick="return confirm('¿Está seguro de desactivar (eliminación lógica) esta carrera?')">
                                <i class="fas fa-trash"></i> Desactivar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>
 