<?php
/**
 * Vista COMÚN para el Foro (Usada por Pacientes y Médicos).
 * * Variables requeridas del controlador:
 * $listaPublicaciones (array)
 * $actionForm (string) -> La acción a donde se envía el formulario ('verForo' o 'verForoMedico')
 */
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Foro de la Comunidad HealthyView</h1>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">¡Publicación realizada con éxito!</div>
<?php endif; ?>
<?php if (isset($errorMessage)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
            <div class="card-body p-4">
                <h5 class="card-title mb-3"><i class="bi bi-pencil-square"></i> Nueva Publicación</h5>
                
                <form action="index.php?action=<?php echo $actionForm; ?>" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título (Opcional):</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ej: Mi avance de hoy">
                    </div>
                    
                    <div class="mb-3">
                        <label for="contenido" class="form-label">Mensaje:</label>
                        <textarea class="form-control" id="contenido" name="contenido" rows="4" placeholder="Comparte algo con la comunidad..." required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Foto (Opcional):</label>
                        <input class="form-control form-control-sm" type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="publicarMensaje" class="btn btn-primary">
                            <i class="bi bi-send-fill"></i> Publicar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <?php if (empty($listaPublicaciones)): ?>
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-chat-square-text fs-1"></i><br>
                Aún no hay publicaciones. ¡Sé el primero en compartir!
            </div>
        <?php else: ?>
            <?php foreach ($listaPublicaciones as $pub): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <?php if ($pub['autorRol'] == 'Medico'): ?>
                                    <div class="avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-heart-pulse-fill"></i>
                                    </div>
                                <?php elseif ($pub['autorRol'] == 'Admin'): ?>
                                    <div class="avatar bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-shield-fill"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($pub['autorNombre']); ?></h6>
                                <small class="text-muted"><?php echo date("d M Y, h:i A", strtotime($pub['fechaPublicacion'])); ?></small>
                            </div>
                        </div>

                        <?php if (!empty($pub['titulo'])): ?>
                            <h5 class="card-title"><?php echo htmlspecialchars($pub['titulo']); ?></h5>
                        <?php endif; ?>
                        
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($pub['contenido'])); ?></p>

                        <?php if (!empty($pub['imagenURL'])): ?>
                            <div class="mb-3">
                                <img src="<?php echo htmlspecialchars($pub['imagenURL']); ?>" class="img-fluid rounded" style="max-height: 400px; w-100 object-fit: cover;" alt="Imagen adjunta">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0">
                        <?php 
                            $btnClass = ($pub['usuarioYaReacciono'] > 0) ? 'btn-danger' : 'btn-outline-danger';
                            $icono = ($pub['usuarioYaReacciono'] > 0) ? 'bi-heart-fill' : 'bi-heart';
                        ?>
                        <a href="index.php?action=reaccionarForo&idPublicacion=<?php echo $pub['idPublicacion']; ?>" 
                           class="btn btn-sm <?php echo $btnClass; ?> rounded-pill px-3">
                            <i class="bi <?php echo $icono; ?>"></i> 
                            <?php echo htmlspecialchars($pub['totalReacciones']); ?> Me gusta
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>