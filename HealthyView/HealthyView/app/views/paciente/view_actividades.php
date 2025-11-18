<?php
/**
 * Vista para "Mis Actividades de Salud" (Paciente).
 * Esta vista es "inyectada" por layout_paciente.php
 *
 * Variables disponibles:
 * $listaActividades (array): Lista de actividades asignadas.
 * $activePage (string): 'actividades'.
 * $successMessage (string|null): Mensaje de éxito.
 * $errorMessage (string|null): Mensaje de error.
 */
?>

<!-- Encabezado del contenido -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Mis Actividades de Salud</h1>
</div>

<!-- Mensajes de Alerta -->
<?php if (isset($successMessage)): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($successMessage); ?>
    </div>
<?php endif; ?>
<?php if (isset($errorMessage)): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($errorMessage); ?>
    </div>
<?php endif; ?>

<!-- Lista de Actividades Asignadas -->
<div class="row">
    <?php if (empty($listaActividades)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                Tu médico aún no te ha asignado ninguna actividad.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($listaActividades as $act): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm <?php echo ($act['estado'] == 'Completada') ? 'border-success' : 'border-primary'; ?>">
                    <div class="card-header <?php echo ($act['estado'] == 'Completada') ? 'bg-success text-white' : 'bg-primary text-white'; ?>">
                        <h5 class="mb-0"><?php echo htmlspecialchars($act['actividadNombre']); ?></h5>
                        <small><?php echo htmlspecialchars($act['actividadTipo']); ?> | <?php echo htmlspecialchars($act['actividadFrecuencia']); ?></small>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo htmlspecialchars($act['actividadDescripcion']); ?></p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item px-0">
                                <strong>Asignada por:</strong> <?php echo htmlspecialchars($act['medicoNombre'] ?? 'Sistema'); ?>
                            </li>
                            <li class="list-group-item px-0">
                                <strong>Fechas:</strong> 
                                <?php echo htmlspecialchars(date("d/m/Y", strtotime($act['fechaInicio'] ?? $act['fechaAsignacion']))); ?>
                                <?php if ($act['fechaFin']): ?>
                                    al <?php echo htmlspecialchars(date("d/m/Y", strtotime($act['fechaFin']))); ?>
                                <?php endif; ?>
                            </li>
                            <li class="list-group-item px-0">
                                <strong>Observaciones:</strong> <?php echo htmlspecialchars($act['medicoObservaciones'] ?? 'Sin observaciones.'); ?>
                            </li>
                        </ul>
                        
                        <!-- Formulario de Progreso (RFN-10) -->
                        <form action="index.php?action=actualizarProgreso" method="POST">
                            <input type="hidden" name="idAsignacion" value="<?php echo $act['idAsignacion']; ?>">
                            
                            <div class="mb-2">
                                <label class="form-label fw-bold text-primary">¿Cuál es el estado de esta actividad?</label>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="respuesta_paciente" id="opcion1-<?php echo $act['idAsignacion']; ?>" value="0" <?php echo ($act['progreso'] < 50) ? 'checked' : ''; ?> <?php echo ($act['estado'] == 'Completada') ? 'disabled' : ''; ?>>
                                    <label class="form-check-label" for="opcion1-<?php echo $act['idAsignacion']; ?>">
                                        No la he iniciado aún (0%)
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="respuesta_paciente" id="opcion2-<?php echo $act['idAsignacion']; ?>" value="50" <?php echo ($act['progreso'] >= 50 && $act['progreso'] < 100) ? 'checked' : ''; ?> <?php echo ($act['estado'] == 'Completada') ? 'disabled' : ''; ?>>
                                    <label class="form-check-label" for="opcion2-<?php echo $act['idAsignacion']; ?>">
                                        En proceso / Avanzando (50%)
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="respuesta_paciente" id="opcion3-<?php echo $act['idAsignacion']; ?>" value="100" <?php echo ($act['progreso'] == 100) ? 'checked' : ''; ?> <?php echo ($act['estado'] == 'Completada') ? 'disabled' : ''; ?>>
                                    <label class="form-check-label" for="opcion3-<?php echo $act['idAsignacion']; ?>">
                                        ¡Actividad Completada! (100%)
                                    </label>
                                </div>
                            </div>
                            
                            <?php if ($act['estado'] != 'Completada'): ?>
                                <div class="d-grid mt-2">
                                    <button type="submit" name="actualizarProgreso" class="btn btn-sm btn-primary">
                                        Actualizar Estatus
                                    </button>
                                </div>
                            <?php endif; ?>
                        </form>
                        
                    </div>
                    <?php if ($act['estado'] == 'Completada'): ?>
                        <div class="card-footer bg-light text-success text-center">
                            <strong>¡Actividad Completada!</strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>