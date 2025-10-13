<?php 
/**
 * Vista: Lista de Estudiantes con opciones de CRUD y Gestión de Inscripciones separadas.
 * Extiende el layout principal 'templates/layout'.
 */
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="container mt-5">
    
    <!-- 1. ALERTAS (Mensajes de éxito/error) -->
    <?= view('templates/_alerts') ?>

    <!-- 2. SECCIÓN PRINCIPAL: LISTA DE ESTUDIANTES (CRUD) -->
    <h1 class="text-center mb-4">Gestión de Estudiantes</h1>
    
    <div class="section-header mb-4">
        <h2><i class="fas fa-graduation-cap"></i> Listado de Alumnos</h2>
        <!-- Botón de Registro con el estilo consistente -->
        <a href="<?= base_url('estudiantes/crear') ?>" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Crear Nuevo Estudiante
        </a>
    </div>

    <!-- Tabla de Estudiantes (AHORA USANDO CLASES CSS PERSONALIZADAS: data-table) -->
    <div class="table-responsive">
        <!-- Reemplazamos 'table table-hover' por la clase personalizada 'data-table' -->
        <table class="data-table">
            <!-- El encabezado se estiliza automáticamente con .data-table thead th -->
            <thead> 
                <tr>
                    <th>ID</th>
                    <th>DNI</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Carrera</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($estudiantes)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">No hay estudiantes registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($estudiantes as $estudiante): ?>
                    <tr>
                        <td class="align-middle"><?= esc($estudiante['id_alumno']) ?></td>
                        <td class="align-middle"><?= esc($estudiante['dni'] ?? 'N/A') ?></td> 
                        <td class="align-middle"><?= esc($estudiante['nombre_completo']) ?></td>
                        <td class="align-middle"><?= esc($estudiante['email'] ?? 'N/A') ?></td>
                        <td class="align-middle"><?= esc($carreras_map[$estudiante['id_carrera']] ?? 'N/A') ?></td>
                        
                        <!-- Columna de Acciones (Editar/Eliminar) -->
                        <td class="align-middle action-buttons">
                            <!-- Usando clases btn-action y btn-edit/btn-delete del styles.css -->
                            <a href="<?= base_url("estudiantes/editar/{$estudiante['id_alumno']}") ?>" class="btn-action btn-edit" title="Editar Alumno">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="<?= base_url("estudiantes/eliminar/{$estudiante['id_alumno']}") ?>" class="btn-action btn-delete" title="Eliminar Alumno" onclick="return confirm('¿Está seguro de eliminar a este estudiante?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Separador visual -->
    <hr class="my-5"> 

    <!-- 3. SECCIÓN SECUNDARIA: GESTIÓN DE INSCRIPCIONES (para el rol específico) -->
    <div class="section-header mt-5 mb-4">
        <h2><i class="fas fa-list-alt"></i> Gestión de Inscripciones Rápidas</h2>
    </div>
    
    <!-- Tabla de Inscripciones Rápidas -->
    <div class="table-responsive">
        <!-- Se mantiene .data-table pero podemos usar un estilo inline en thead para diferenciar -->
        <table class="data-table">
            <!-- Usamos un estilo inline para forzar el color gris oscuro en este encabezado, si es necesario -->
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
                                <?php 
                                $cursos_inscritos_str = 'No inscrito en cursos.';
                                if (!empty($inscripciones_por_alumno[$estudiante['id_alumno']])): 
                                    $nombres_cursos = [];
                                    foreach ($inscripciones_por_alumno[$estudiante['id_alumno']] as $inscripcion):
                                        $nombres_cursos[] = esc($cursos_map[$inscripcion['id_curso']] ?? 'Curso Desconocido');
                                    endforeach;
                                    $cursos_inscritos_str = implode(', ', $nombres_cursos);
                                endif;
                                echo $cursos_inscritos_str;
                                ?>
                            </td>

                            <!-- Formulario de Inscripción Rápida -->
                            <td class="align-middle">
                                <form action="<?= base_url('inscripciones/inscribir') ?>" method="post" class="d-flex" style="gap: 5px;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_alumno" value="<?= esc($estudiante['id_alumno']) ?>">
                                    
                                    <!-- Usando la clase form-control de tu CSS -->
                                    <select name="id_curso" class="form-control" style="width: 100%; max-width: 200px;" required>
                                        <option value="">Seleccione Curso</option>
                                        <?php foreach ($cursos as $curso): ?>
                                            <option value="<?= esc($curso['id_curso']) ?>">
                                                <?= esc($curso['nombre_curso']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    
                                    <!-- Usando un botón de acción success/info -->
                                    <button type="submit" class="btn-action btn-edit" title="Inscribir">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            </td>

                            <!-- Botón de Desinscripción (solo si está inscrito en algo) -->
                            <td class="align-middle">
                                <?php if (!empty($inscripciones_por_alumno[$estudiante['id_alumno']])): ?>
                                    <!-- Botón de acción danger/delete -->
                                    <a href="<?= base_url('inscripciones/desinscribir/' . esc($estudiante['id_alumno'])) ?>" 
                                       class="btn-action btn-delete" 
                                       title="Desinscribir de todos los cursos"
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

</div>

<?= $this->endSection() ?>
 