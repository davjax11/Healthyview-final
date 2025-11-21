<?php
/**
 * Vista de Reportes (Administrador)
 * Incluye Tablas de Excel y GRÁFICAS.
 */
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Reportes y Estadísticas</h1>
</div>

<div class="row mb-4">
    
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary fw-bold">Eficiencia de Citas</h5>
                <a href="index.php?action=exportarReporte&tipo=estados_cita" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-file-excel"></i> Excel
                </a>
            </div>
            <div class="card-body">
                <canvas id="chartCitas"></canvas> </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-success fw-bold">Salud Global (IMC)</h5>
                <a href="index.php?action=exportarReporte&tipo=salud_imc" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-file-excel"></i> Excel
                </a>
            </div>
            <div class="card-body">
                <canvas id="chartIMC"></canvas> </div>
        </div>
    </div>
</div>

<hr class="my-4">

<h4 class="mb-3 text-secondary">Detalle de Operaciones</h4>
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pacientes por Género</h5>
                <a href="index.php?action=exportarReporte&tipo=generos" class="btn btn-sm btn-success"><i class="bi bi-download"></i> Excel</a>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Género</th><th>Total</th></tr></thead>
                    <tbody>
                        <?php foreach ($reporteGeneros as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['genero']); ?></td>
                                <td><?php echo htmlspecialchars($item['total']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Citas por Médico</h5>
                <a href="index.php?action=exportarReporte&tipo=citas" class="btn btn-sm btn-success"><i class="bi bi-download"></i> Excel</a>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Médico</th><th>Especialidad</th><th>Total</th></tr></thead>
                    <tbody>
                        <?php foreach ($reporteCitas as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['medicoNombre']); ?></td>
                                <td><?php echo htmlspecialchars($item['especialidad']); ?></td>
                                <td><?php echo htmlspecialchars($item['totalCitas']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Datos para Gráfico de CITAS (Pastel)
    const ctxCitas = document.getElementById('chartCitas').getContext('2d');
    const dataCitas = {
        labels: [<?php foreach($reporteEstadoCitas as $r) echo '"' . $r['estado'] . '",'; ?>],
        datasets: [{
            data: [<?php foreach($reporteEstadoCitas as $r) echo $r['total'] . ','; ?>],
            backgroundColor: ['#0d6efd', '#198754', '#dc3545', '#ffc107'], // Colores Bootstrap
        }]
    };
    new Chart(ctxCitas, {
        type: 'doughnut', // Gráfico de Dona
        data: dataCitas,
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // 2. Datos para Gráfico de IMC (Barras)
    const ctxIMC = document.getElementById('chartIMC').getContext('2d');
    const dataIMC = {
        labels: [<?php foreach($reporteIMC as $r) echo '"' . $r['categoria'] . '",'; ?>],
        datasets: [{
            label: 'Pacientes',
            data: [<?php foreach($reporteIMC as $r) echo $r['total'] . ','; ?>],
            backgroundColor: [
                'rgba(255, 193, 7, 0.6)', // Bajo peso (Amarillo)
                'rgba(25, 135, 84, 0.6)', // Normal (Verde)
                'rgba(253, 126, 20, 0.6)', // Sobrepeso (Naranja)
                'rgba(220, 53, 69, 0.6)'  // Obesidad (Rojo)
            ],
            borderColor: [
                '#ffc107', '#198754', '#fd7e14', '#dc3545'
            ],
            borderWidth: 1
        }]
    };
    new Chart(ctxIMC, {
        type: 'bar', // Gráfico de Barras
        data: dataIMC,
        options: { 
            responsive: true, 
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>