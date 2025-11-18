<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Clínico - <?php echo htmlspecialchars($datosPaciente['nombre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', serif; background: #fff; color: #000; }
        .header-reporte { border-bottom: 2px solid #0d6efd; padding-bottom: 20px; margin-bottom: 30px; }
        .seccion-titulo { background-color: #f8f9fa; padding: 5px 10px; border-left: 5px solid #0d6efd; margin-top: 30px; margin-bottom: 15px; font-weight: bold; text-transform: uppercase; }
        .firma-box { margin-top: 80px; text-align: center; }
        .firma-linea { border-top: 1px solid #000; width: 250px; margin: 0 auto; }
        
        /* Estilos específicos para Impresión */
        @media print {
            .no-print { display: none !important; } /* Ocultar botones al imprimir */
            body { padding: 0; }
            .container { width: 100%; max-width: 100%; }
            a { text-decoration: none; color: #000; }
        }
    </style>
</head>
<body class="p-4">

    <div class="container">
        <div class="text-end mb-4 no-print">
            <button onclick="window.print()" class="btn btn-primary btn-lg">
                <i class="bi bi-printer"></i> Imprimir / Guardar como PDF
            </button>
            <button onclick="window.close()" class="btn btn-secondary btn-lg">Cerrar</button>
        </div>

        <div class="header-reporte d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-0 text-primary">HealthyView</h1>
                <small>Sistema de Monitoreo Nutricional y Hábitos Saludables</small>
            </div>
            <div class="text-end">
                <h5>Reporte Clínico Integral</h5>
                <p class="mb-0">Fecha de Emisión: <strong><?php echo date('d/m/Y'); ?></strong></p>
                <p class="mb-0">Médico: <strong>Dr. <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong></p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="seccion-titulo">Datos del Paciente</div>
                <table class="table table-bordered table-sm">
                    <tr>
                        <th width="20%">Nombre Completo:</th>
                        <td><?php echo htmlspecialchars($datosPaciente['nombre'] . ' ' . $datosPaciente['apellidoPaterno'] . ' ' . ($datosPaciente['apellidoMaterno'] ?? '')); ?></td>
                        <th width="15%">Edad/Género:</th>
                        <td>
                            <?php 
                            $edad = date_diff(date_create($datosPaciente['fechaNacimiento']), date_create('now'))->y;
                            echo $edad . ' años / ' . htmlspecialchars($datosPaciente['genero']); 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Correo:</th>
                        <td><?php echo htmlspecialchars($datosPaciente['correo']); ?></td>
                        <th>Teléfono:</th>
                        <td><?php echo htmlspecialchars($datosPaciente['telefono'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Diagnóstico Base:</th>
                        <td colspan="3"><?php echo nl2br(htmlspecialchars($datosPaciente['diagnostico'] ?? 'Sin diagnóstico registrado.')); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="seccion-titulo">Últimos Registros de Evolución</div>
        <?php if (empty($historialSeguimiento)): ?>
            <p class="text-muted fst-italic">No hay registros de seguimiento.</p>
        <?php else: ?>
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Peso (kg)</th>
                        <th>IMC</th>
                        <th>Bienestar</th>
                        <th>Observaciones Médicas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($historialSeguimiento, 0, 5) as $seg): ?>
                    <tr>
                        <td><?php echo date("d/m/Y", strtotime($seg['fechaRegistro'])); ?></td>
                        <td><?php echo $seg['peso']; ?></td>
                        <td><?php echo number_format($seg['imc'], 2); ?></td>
                        <td><?php echo $seg['nivelBienestar']; ?></td>
                        <td><?php echo htmlspecialchars($seg['observaciones']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="seccion-titulo">Recetas y Planes Recientes</div>
        <?php if (empty($historialRecetas)): ?>
            <p class="text-muted fst-italic">No hay recetas registradas.</p>
        <?php else: ?>
            <?php foreach (array_slice($historialRecetas, 0, 3) as $receta): ?>
                <div class="border p-2 mb-2 rounded">
                    <strong><?php echo date("d/m/Y", strtotime($receta['fechaEmision'])); ?> - <?php echo htmlspecialchars($receta['resumen']); ?></strong>
                    <span class="badge border text-dark float-end"><?php echo $receta['estado']; ?></span>
                    <p class="mb-0 small text-muted"><?php echo htmlspecialchars($receta['observaciones']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="firma-box">
            <br><br><br>
            <div class="firma-linea"></div>
            <p class="mb-0"><strong>Dr. <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong></p>
            <small>Firma del Médico Tratante</small>
        </div>

        <div class="fixed-bottom text-center p-2 bg-white no-print">
            <small class="text-muted">Documento generado electrónicamente por HealthyView el <?php echo date('d/m/Y H:i:s'); ?></small>
        </div>
    </div>

</body>
</html>