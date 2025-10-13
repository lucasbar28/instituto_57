<?php 
/**
 * Archivo de plantilla para mostrar alertas de sesión (éxito, error, advertencia)
 * Utiliza la función view('templates/_alerts') para ser incluido en otras vistas.
 */
?>

<!-- 1. Mensaje de Éxito (Generalmente después de guardar o actualizar) -->
<?php if (session()->getFlashdata('success')): ?>
    <!-- Usamos una clase de alerta con estilo de éxito consistente -->
    <div class="alert alert-success section-box">
        <i class="fas fa-check-circle"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<!-- 2. Mensaje de Error (Usado para errores críticos o de servidor) -->
<?php if (session()->getFlashdata('error')): ?>
    <!-- Usamos una clase de alerta con estilo de error consistente -->
    <div class="alert alert-danger section-box">
        <i class="fas fa-times-circle"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- 3. Mensaje de Advertencia (Usado a menudo para validación o advertencias) -->
<?php if (session()->getFlashdata('warning')): ?>
    <!-- Usamos una clase de alerta con estilo de advertencia consistente -->
    <div class="alert alert-warning section-box">
        <i class="fas fa-exclamation-triangle"></i>
        <?= session()->getFlashdata('warning') ?>
    </div>
<?php endif; ?>
 