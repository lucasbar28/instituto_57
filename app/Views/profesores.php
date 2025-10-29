<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="container mt-5">
    
    <!-- 1. ALERTAS (Mensajes de éxito/error) -->
    <!-- Replicamos la inclusión de _alerts, pero manejamos la lógica de mensajes directamente si es necesario. -->
    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-success mt-3" role="alert">
            <?= session()->getFlashdata('mensaje') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mt-3" role="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- 2. TÍTULO Y BOTÓN DE CREACIÓN -->
    <!-- Usamos la misma estructura de centrado que carreras.php: d-flex flex-column align-items-center justify-content-center -->
    <div class="d-flex flex-column align-items-center justify-content-center mb-5">
        <!-- Título principal -->
        <h1 class="display-5 fw-bold text-primary mb-3">Lista de Profesores</h1>
        
        <!-- Botón de Creación -->
        <a href="<?= base_url('profesores/crear') ?>" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus-circle"></i> Registrar Nuevo Profesor
        </a>
    </div>

    <!-- 3. CONTENEDOR DE LA TABLA -->
    <!-- Usamos 'table-responsive' y tu clase 'data-table' -->
    <div class="table-responsive">
        <table class="data-table"> 
            
            <!-- ENCABEZADO -->
            <thead> 
                <!-- Las columnas son diferentes a las de carreras, usamos las columnas de profesor -->
                <tr>
                    <th class="col-1">ID</th>
                    <th class="col-3">Nombre Completo</th>
                    <th class="col-2 text-center">Especialidad</th>
                    <th class="col-3">Email</th>
                    <th class="col-2 text-center">Teléfono</th>
                    <th class="col-1 text-center">Acciones</th>
                </tr>
            </thead>
            
            <!-- CUERPO DE LA TABLA -->
            <tbody>
                <?php 
                // Usamos $profesores que viene del controlador
                $data_profesores = $profesores ?? [];
                ?>

                <?php if (empty($data_profesores)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">No hay profesores registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data_profesores as $profesor): ?>
                    <tr>
                        <!-- DATOS DEL PROFESOR -->
                        <td class="align-middle"><?= esc($profesor['id_profesor']) ?></td>
                        <td class="align-middle"><?= esc($profesor['nombre_completo']) ?></td>
                        <td class="align-middle text-center"><?= esc($profesor['especialidad']) ?></td>
                        <td class="align-middle"><?= esc($profesor['email']) ?></td>
                        <td class="align-middle text-center"><?= esc($profesor['telefono'] ?? 'N/A') ?></td>
                        
                        <!-- ACCIONES -->
                        <td class="align-middle action-buttons text-center"> 
                            <!-- Botón Editar: Usando la clase personalizada btn-edit y fa-edit (asumimos Font Awesome) -->
                            <a href="<?= base_url("profesores/editar/{$profesor['id_profesor']}") ?>" 
                               class="btn-action btn-edit" title="Editar Profesor">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            
                            <!-- Botón Eliminar: Usando la clase personalizada btn-delete. Mantenemos el formulario DELETE. -->
                            <form action="<?= base_url('profesores/eliminar/' . $profesor['id_profesor']) ?>" 
                                  method="post" style="display:inline;"
                                  onsubmit="return confirm('⚠️ ADVERTENCIA: Esta acción es PERMANENTE. ¿Está seguro de que desea BORRAR DEFINITIVAMENTE al profesor <?= esc($profesor['nombre_completo']) ?>?');">
                                
                                <input type="hidden" name="_method" value="DELETE"> 
                                <button type="submit" class="btn-action btn-delete" title="Borrar Permanentemente">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>
 