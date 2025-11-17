<?php 
/**
 * Vista: Lista de Estudiantes con opciones de CRUD y Gestión de Inscripciones separadas.
 * Extiende el layout principal 'templates/layout'.
 */
$rol = session()->get('rol'); // Obtenemos el rol del usuario para restringir la vista
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="container mt-5">
    
    <!-- 1. ALERTAS (Mensajes de éxito/error) -->
    <?= view('templates/_alerts') ?>

    <!-- 1.B APP -->
    <section class="steps-section">
    <div class="container">
        <h2 class="section-title"><i class="fas fa-user-graduate"></i> Mi Vida Académica: Rol del Alumno</h2>
        <p class="section-subtitle">Accede y gestiona tu información, calificaciones y materiales de estudio desde tu perfil.</p>
        <div class="steps-grid">
                <div class="step-card">
                <div class="step-number">1</div>
                <h3>Consulta de Notas</h3>
                <p>Visualiza tus calificaciones de exámenes y trabajos prácticos en tiempo real.</p>
                <a href="<?= base_url('alumnos/notas') ?>" class="step-link">Ver Notas <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Material de Cátedra</h3>
                <p>Descarga apuntes, presentaciones y bibliografía compartida por tus profesores.</p>
                <a href="<?= base_url('alumnos/material') ?>" class="step-link">Acceder a Archivos <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Seguimiento de Asistencia</h3>
                <p>Verifica tu porcentaje de asistencia a clases y justifica inasistencias.</p>
                <a href="<?= base_url('alumnos/asistencia') ?>" class="step-link">Revisar Asistencia <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div> 
</section>

    <!-- 2. SECCIÓN PRINCIPAL: LISTA DE ESTUDIANTES (CRUD) -->
    <h1 class="text-center mb-4">Gestión de Estudiantes</h1>
    
    <div class="section-header mb-4 d-flex justify-content-between align-items-center">
        <h2><i class="fas fa-graduation-cap"></i> Listado de Alumnos</h2>
        
        <?php if ($rol === 'administrador'): // Solo Administrador puede Crear ?>
        <a href="<?= base_url('estudiantes/crear') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus"></i> Crear Nuevo Estudiante
        </a>
        <?php endif; ?>
    </div>

    <!-- Tabla de Estudiantes -->
    <div class="table-responsive">
        <table class="data-table">
            <thead> 
                <tr>
                    <th>ID</th>
                    <!-- DNI y Email solo visibles para Admin y Profesor por privacidad -->
                    <?php if ($rol === 'administrador' || $rol === 'profesor'): ?>
                    <th>DNI</th>
                    <?php endif; ?>
                    <th>Nombre Completo</th>
                    <?php if ($rol === 'administrador' || $rol === 'profesor'): ?>
                    <th>Email</th>
                    <?php endif; ?>
                    <th>Carrera</th>
                    
                    <?php if ($rol === 'administrador'): // Solo Administrador tiene acciones CRUD ?>
                    <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($estudiantes)): ?>
                    <tr>
                        <td colspan="<?= ($rol === 'administrador' || $rol === 'profesor') ? 6 : 4 ?>" class="text-center py-4">No hay estudiantes registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($estudiantes as $estudiante): ?>
                    <tr>
                        <td class="align-middle"><?= esc($estudiante['id_alumno']) ?></td>
                        
                        <?php if ($rol === 'administrador' || $rol === 'profesor'): ?>
                        <td class="align-middle"><?= esc($estudiante['dni_matricula'] ?? 'N/A') ?></td> 
                        <?php endif; ?>
                        
                        <td class="align-middle"><?= esc($estudiante['nombre_completo']) ?></td>
                        
                        <?php if ($rol === 'administrador' || $rol === 'profesor'): ?>
                        <td class="align-middle"><?= esc($estudiante['email'] ?? 'N/A') ?></td>
                        <?php endif; ?>
                        
                        <td class="align-middle"><?= esc($carreras_map[$estudiante['id_carrera']] ?? 'N/A') ?></td>
                        
                        <!-- Columna de Acciones (Editar/Eliminar) - SOLO ADMINISTRADOR -->
                        <?php if ($rol === 'administrador'): ?>
                        <td class="align-middle">
                            <a href="<?= base_url("estudiantes/editar/{$estudiante['id_alumno']}") ?>" class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= base_url("estudiantes/eliminar/{$estudiante['id_alumno']}") ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar a este estudiante?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Separador visual -->
    <hr class="my-5"> 

    <!-- 3. SECCIÓN SECUNDARIA: GESTIÓN DE INSCRIPCIONES (Solo para Administrador y Profesor) -->
    <?php if ($rol === 'administrador' || $rol === 'profesor'): ?>
    
    <div class="section-header mt-5 mb-4">
        <h2><i class="fas fa-list-alt"></i> Gestión de Inscripciones Rápidas</h2>
    </div>
    
    <!-- Tabla de Inscripciones Rápidas -->
    <div class="table-responsive">
        <table class="data-table">
            <thead style="background-color: #6b7280; color: white;"> 
                <tr>
                    <th>Estudiante</th>
                    <th>Cursos Inscritos</th>
                    <th>Inscribir a Curso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($estudiantes)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4">No hay estudiantes para gestionar inscripciones.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($estudiantes as $estudiante): ?>
                        <tr>
                            <td class="align-middle"><?= esc($estudiante['nombre_completo']) ?></td>
                            
                            <!-- Cursos Inscritos -->
                            <td class="align-middle">
                                <?php if (!empty($inscripciones_por_alumno[$estudiante['id_alumno']])): ?>
                                    <?php foreach ($inscripciones_por_alumno[$estudiante['id_alumno']] as $inscripcion): ?>
                                        <?= esc($inscripcion['nombre_curso']) ?><br>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">No inscrito en cursos.</span>
                                <?php endif; ?>
                            </td>

                            <!-- Formulario de Inscripción Rápida -->
                            <td class="align-middle">
                                <form action="<?= base_url('inscripciones/inscribir') ?>" method="post" class="d-flex" style="gap: 5px;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_alumno" value="<?= esc($estudiante['id_alumno']) ?>">
                                    
                                    <select name="id_curso" class="form-control" style="width: 100%; max-width: 250px;" required>
                                        <option value="">Seleccione Curso</option>
                                        <?php 
                                        // Filtrar cursos solo de la carrera del estudiante
                                        $id_carrera_estudiante = $estudiante['id_carrera'];
                                        $cursos_estudiante = $cursos_por_carrera[$id_carrera_estudiante] ?? [];
                                        
                                        foreach ($cursos_estudiante as $curso): 
                                            $codigo = !empty($curso['codigo']) ? $curso['codigo'] . ' - ' : '';
                                            $anio_texto = !empty($curso['anio']) ? ' (Año ' . $curso['anio'] . ')' : '';
                                            $nombre_completo = $codigo . $curso['nombre'] . $anio_texto;
                                        ?>
                                            <option value="<?= esc($curso['id_curso']) ?>">
                                                <?= esc($nombre_completo) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    
                                    <button type="submit" class="btn btn-sm btn-primary" title="Inscribir">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            </td>

                            <!-- Botón de Desinscripción -->
                            <td class="align-middle">
                                <?php if (!empty($inscripciones_por_alumno[$estudiante['id_alumno']])): ?>
                                    <a href="<?= base_url('inscripciones/desinscribir/' . esc($estudiante['id_alumno'])) ?>" 
                                       class="btn btn-sm btn-danger" 
                                       title="Desinscribir de su último curso"
                                       onclick="return confirm('¿Desea desinscribir a <?= esc($estudiante['nombre_completo']) ?> de su último curso?')">
                                        <i class="fas fa-minus-circle"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>
 