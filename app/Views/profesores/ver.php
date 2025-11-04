<?php 
/**
 * Vista: Muestra los detalles de un profesor específico.
 * Extiende el layout principal 'templates/layout'.
 * Variables esperadas:
 * - $profesor: Array de datos del profesor, incluyendo el campo 'email' (nombre_de_usuario).
 * - $title: Título de la página.
 */
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="font-weight-light my-3">
                        <i class="fas fa-id-card-alt"></i> Detalle del Profesor
                    </h3>
                </div>
                <div class="card-body">
                    
                    <h4 class="mb-4 text-primary"><?= esc($profesor['nombre_completo']) ?></h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Datos Personales y Académicos -->
                            <h5 class="mt-3 mb-3 text-secondary border-bottom pb-2"><i class="fas fa-info-circle me-2"></i>Información Principal</h5>
                            
                            <dl class="row detail-list">
                                <dt class="col-sm-5">ID Profesor:</dt>
                                <dd class="col-sm-7 fw-bold"><?= esc($profesor['id_profesor']) ?></dd>
                                
                                <dt class="col-sm-5">Especialidad:</dt>
                                <dd class="col-sm-7"><?= esc($profesor['especialidad']) ?></dd>
                                
                                <dt class="col-sm-5">Teléfono:</dt>
                                <dd class="col-sm-7"><?= esc($profesor['telefono'] ?? 'N/A') ?></dd>
                            </dl>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- Datos de Acceso y Sistema -->
                            <h5 class="mt-3 mb-3 text-secondary border-bottom pb-2"><i class="fas fa-lock me-2"></i>Credenciales de Sistema</h5>
                            
                            <dl class="row detail-list">
                                <dt class="col-sm-5">Usuario (Email):</dt>
                                <dd class="col-sm-7 text-break"><?= esc($profesor['email']) ?></dd>
                                
                                <dt class="col-sm-5">ID de Usuario:</dt>
                                <dd class="col-sm-7"><?= esc($profesor['id_usuario']) ?></dd>
                                
                                <dt class="col-sm-5">Creado en:</dt>
                                <dd class="col-sm-7"><?= esc($profesor['created_at'] ?? 'N/A') ?></dd>
                                
                                <dt class="col-sm-5">Última Modificación:</dt>
                                <dd class="col-sm-7"><?= esc($profesor['updated_at'] ?? 'N/A') ?></dd>
                            </dl>
                        </div>
                    </div>
                    
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="<?= base_url('profesores') ?>" class="btn btn-secondary">
                        <i class="fas fa-chevron-left me-1"></i> Volver a la Lista
                    </a>
                    <?php if (session()->get('rol') === 'administrador'): ?>
                        <a href="<?= base_url('profesores/editar/' . $profesor['id_profesor']) ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Editar Profesor
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.detail-list dt {
    color: #495057;
    font-weight: 500;
}
.detail-list dd {
    margin-bottom: 10px;
}
</style>

<?= $this->endSection() ?>
 