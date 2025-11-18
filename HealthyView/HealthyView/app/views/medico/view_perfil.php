<?php
/**
 * Vista "Mi Perfil" (Médico).
 * Variables: $datosMedico (array)
 */

$successMessage = $_GET['success'] ?? null;
$errorMessage = $_GET['error'] ?? null;
$errorMap = [
    'pass_no_coinciden' => 'Las contraseñas no coinciden.',
    'correo_existe' => 'El correo electrónico ya está registrado por otro usuario.',
    'true' => 'Ocurrió un error al guardar los cambios.'
];
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Mi Perfil Profesional</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-md-5">

        <?php if ($successMessage): ?>
            <div class="alert alert-success">¡Perfil actualizado exitosamente!</div>
        <?php endif; ?>
        <?php if ($errorMessage && isset($errorMap[$errorMessage])): ?>
            <div class="alert alert-danger"><?php echo $errorMap[$errorMessage]; ?></div>
        <?php endif; ?>
        
        <form action="index.php?action=updateProfile" method="POST">
            
            <h5 class="mb-3 text-primary">Información Personal</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nombre" class="form-label">Nombre(s):</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($datosMedico['nombre']); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="apellidoPaterno" class="form-label">Apellido Paterno:</label>
                    <input type="text" class="form-control" id="apellidoPaterno" name="apellidoPaterno" value="<?php echo htmlspecialchars($datosMedico['apellidoPaterno']); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="apellidoMaterno" class="form-label">Apellido Materno:</label>
                    <input type="text" class="form-control" id="apellidoMaterno" name="apellidoMaterno" value="<?php echo htmlspecialchars($datosMedico['apellidoMaterno']); ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="correo" class="form-label">Correo Electrónico:</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($datosMedico['correo']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Teléfono:</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($datosMedico['telefono'] ?? ''); ?>">
                </div>
            </div>

            <h5 class="mt-4 mb-3 text-primary">Información Profesional</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Especialidad:</label>
                    <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($datosMedico['especialidad']); ?>" readonly>
                    <div class="form-text">Contacta al administrador para cambiar esto.</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Cédula Profesional:</label>
                    <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($datosMedico['cedulaProfesional']); ?>" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Disponibilidad:</label>
                    <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($datosMedico['disponibilidad']); ?>" readonly>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="mb-3 text-danger">Seguridad</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pass" class="form-label">Nueva Contraseña (Opcional):</label>
                    <input type="password" class="form-control" id="pass" name="pass" placeholder="Dejar en blanco para mantener la actual">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="pass_confirm" class="form-label">Confirmar Nueva Contraseña:</label>
                    <input type="password" class="form-control" id="pass_confirm" name="pass_confirm">
                </div>
            </div>

            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary btn-lg" name="actualizar">Guardar Cambios</button>
            </div>

        </form>
    </div>
</div>