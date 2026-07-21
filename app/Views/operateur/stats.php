<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="/assets/chart.js"></script>
    <?= $this->include('partials/styles') ?>
    <style>
        .chart-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 20px; margin-bottom: 24px; }
        .chart-card h3 { font-size: 1.1rem; color: #555; margin-bottom: 16px; text-align: center; }
        .stat-summary { text-align: center; padding: 16px; }
        .stat-summary .value { font-size: 1.8rem; font-weight: 700; color: #2563eb; }
        .stat-summary .label { font-size: 0.85rem; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-summary.intra .value { color: #16a34a; }
        .stat-summary.inter .value { color: #dc2626; }
        .stat-summary.comm .value { color: #d97706; }
    </style>
</head>
<body>
<?= $this->include('partials/operateur_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1 class="mb-4">Tableau de bord statistique</h1>

    <!-- Cartes résumé -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="chart-card stat-summary intra">
                <div class="value"><?= number_format($totalIntra, 0, ',', ' ') ?> Ar</div>
                <div class="label">Frais intra-operateur</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="chart-card stat-summary inter">
                <div class="value"><?= number_format($totalInter, 0, ',', ' ') ?> Ar</div>
                <div class="label">Frais inter-operateurs</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="chart-card stat-summary">
                <div class="value"><?= number_format($totalGeneral, 0, ',', ' ') ?> Ar</div>
                <div class="label">Total des frais</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="chart-card stat-summary comm">
                <div class="value"><?= number_format($totalCommissions, 0, ',', ' ') ?> Ar</div>
                <div class="label">Commissions a reverser</div>
            </div>
        </div>
    </div>

    <!-- Ligne 1: Doughnut (intra vs inter) + Bar (frais par type) -->
    <div class="row">
        <div class="col-md-5">
            <div class="chart-card">
                <h3>Repartition des frais</h3>
                <canvas id="doughnutChart" height="280"></canvas>
            </div>
        </div>
        <div class="col-md-7">
            <div class="chart-card">
                <h3>Frais par type d'operation</h3>
                <canvas id="barFraisChart" height="280"></canvas>
            </div>
        </div>
    </div>

    <!-- Ligne 2: Polar (commissions) + Pie (clients) -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-card">
                <h3>Commissions dues par operateur</h3>
                <canvas id="polarChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-card">
                <h3>Clients par operateur</h3>
                <canvas id="pieChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Ligne 3: Bar horizontale (soldes par operateur) -->
    <div class="row">
        <div class="col-md-12">
            <div class="chart-card">
                <h3>Situation des soldes par operateur</h3>
                <canvas id="barSoldeChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// ======================== DONUT : Intra vs Inter ========================
new Chart(document.getElementById('doughnutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Intra-operateur', 'Inter-operateurs'],
        datasets: [{
            data: [<?= $totalIntra ?>, <?= $totalInter ?>],
            backgroundColor: ['#16a34a', '#dc2626'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.label + ': ' + Number(ctx.raw).toLocaleString('fr-FR') + ' Ar'
                }
            }
        }
    }
});

// ======================== BAR : Frais par type ========================
<?php
$typeLabels = [];
$typeTotals = [];
$typeCounts = [];
foreach ($parType as $t) {
    $typeLabels[] = $t['typeNom'];
    $typeTotals[] = (float) $t['total'];
    $typeCounts[] = (int) $t['nombre'];
}
?>
new Chart(document.getElementById('barFraisChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($typeLabels) ?>,
        datasets: [
            {
                label: 'Total frais (Ar)',
                data: <?= json_encode($typeTotals) ?>,
                backgroundColor: '#3b82f6',
                borderRadius: 6,
                yAxisID: 'y',
            },
            {
                label: "Nombre d'operations",
                data: <?= json_encode($typeCounts) ?>,
                backgroundColor: '#f59e0b',
                borderRadius: 6,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Montant (Ar)' },
                ticks: { callback: v => v.toLocaleString('fr-FR') }
            },
            y1: {
                beginAtZero: true,
                position: 'right',
                title: { display: true, text: 'Nombre' },
                grid: { display: false },
            }
        }
    }
});

// ======================== POLAR : Commissions dues ========================
<?php
$commLabels = [];
$commTotaux = [];
foreach ($commissionsDues as $c) {
    $commLabels[] = $c['sourceLabelle'] . ' → ' . $c['destinataireLabelle'];
    $commTotaux[] = (float) $c['montantCommission'];
}
?>
new Chart(document.getElementById('polarChart'), {
    type: 'polarArea',
    data: {
        labels: <?= json_encode($commLabels) ?>,
        datasets: [{
            data: <?= json_encode($commTotaux) ?>,
            backgroundColor: ['#3b82f6', '#ef4444', '#16a34a', '#f59e0b', '#8b5cf6', '#ec4899'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.label + ': ' + Number(ctx.raw).toLocaleString('fr-FR') + ' Ar'
                }
            }
        }
    }
});

// ======================== PIE : Clients par operateur ========================
<?php
$opLabels = [];
$opCounts = [];
foreach ($clientsParOperateur as $op) {
    $opLabels[] = $op['labelle'];
    $opCounts[] = (int) $op['total'];
}
?>
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($opLabels) ?>,
        datasets: [{
            data: <?= json_encode($opCounts) ?>,
            backgroundColor: ['#3b82f6', '#ef4444', '#16a34a', '#f59e0b', '#8b5cf6'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.label + ': ' + ctx.raw + ' client(s)'
                }
            }
        }
    }
});

// ======================== BAR : Soldes par operateur ========================
<?php
$soldeLabels = [];
$soldeTotaux = [];
foreach ($clientsParOperateur as $op) {
    $soldeLabels[] = $op['labelle'];
    $soldeTotaux[] = (float) $op['soldeTotal'];
}
?>
new Chart(document.getElementById('barSoldeChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($soldeLabels) ?>,
        datasets: [{
            label: 'Solde total (Ar)',
            data: <?= json_encode($soldeTotaux) ?>,
            backgroundColor: ['#3b82f6', '#ef4444', '#16a34a'],
            borderRadius: 8,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => Number(ctx.raw).toLocaleString('fr-FR') + ' Ar'
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { callback: v => v.toLocaleString('fr-FR') }
            }
        }
    }
});
</script>
</body>
</html>