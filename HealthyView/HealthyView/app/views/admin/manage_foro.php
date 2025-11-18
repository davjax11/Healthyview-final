<h1 class="h2 border-bottom pb-2 mb-3">Moderación del Foro</h1>
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Autor</th>
                        <th>Contenido</th>
                        <th>Imagen</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaPosts as $post): ?>
                    <tr>
                        <td style="white-space:nowrap;"><?php echo date('d/m/Y H:i', strtotime($post['fechaPublicacion'])); ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($post['autorNombre']); ?></strong><br>
                            <span class="badge bg-secondary"><?php echo $post['autorRol']; ?></span>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($post['titulo']); ?></strong><br>
                            <small><?php echo substr(htmlspecialchars($post['contenido']), 0, 100) . '...'; ?></small>
                        </td>
                        <td>
                            <?php if($post['imagenURL']): ?>
                                <a href="<?php echo $post['imagenURL']; ?>" target="_blank">Ver Imagen</a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?action=manageForo&eliminar=1&id=<?php echo $post['idPublicacion']; ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Estás seguro de ELIMINAR esta publicación permanentemente?');">
                                <i class="bi bi-trash-fill"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>