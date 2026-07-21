<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des gains</title>
    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<?= $this->include('partials/operateur_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Situation des gains</h1>

    <h2 class="mt-4">Frais intra-operateur (meme operateur)</h2>
    <table class="table table-striped">
        <thead><tr><th>Type</th><th>Nombre d'operations</th><th>Total des frais</th></tr></thead>
        <tbody>
        <?php if (empty($fraisIntra)): ?>
            <tr><td colspan="3" class="text-muted">Aucune operation intra-operateur.</td></tr>
        <?php else: ?>
            <?php foreach ($fraisIntra as $ligne): ?>
                <tr>
                    <td><?= esc($ligne['typeNom']) ?></td>
                    <td><?= (int) $ligne['nombre'] ?></td>
                    <td><?= number_format($ligne['total'], 0, ',', ' ') ?> Ar</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <tr><th colspan="2">Total intra-operateur</th><th><?= number_format($totalIntra, 0, ',', ' ') ?> Ar</th></tr>
        </tfoot>
    </table>

    <h2 class="mt-4">Frais inter-operateurs (operateurs differents)</h2>
    <table class="table table-striped">
        <thead><tr><th>Type</th><th>Nombre d'operations</th><th>Total des frais</th></tr></thead>
        <tbody>
        <?php if (empty($fraisInter)): ?>
            <tr><td colspan="3" class="text-muted">Aucune operation inter-operateurs.</td></tr>
        <?php else: ?>
            <?php foreach ($fraisInter as $ligne): ?>
                <tr>
                    <td><?= esc($ligne['typeNom']) ?></td>
                    <td><?= (int) $ligne['nombre'] ?></td>
                    <td><?= number_format($ligne['total'], 0, ',', ' ') ?> Ar</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <tr><th colspan="2">Total inter-operateurs</th><th><?= number_format($totalInter, 0, ',', ' ') ?> Ar</th></tr>
        </tfoot>
    </table>

    <table class="table table-bordered mt-3">
        <tfoot>
            <tr class="table-secondary"><th colspan="2">Total general des frais</th><th><?= number_format($totalGeneral, 0, ',', ' ') ?> Ar</th></tr>
        </tfoot>
    </table>

    <h2 class="mt-5">Commissions inter-operateurs a reverser</h2>
    <p class="text-muted">Montants dus par chaque operateur source a chaque operateur destinataire pour les transferts.</p>
    <table class="table table-striped">
        <thead><tr><th>Operateur source</th><th>Operateur destinataire</th><th>Nombre de transferts</th><th>Montant a reverser</th></tr></thead>
        <tbody>
        <?php if (empty($commissionsDues)): ?>
            <tr><td colspan="4" class="text-muted">Aucune commission inter-operateur a reverser.</td></tr>
        <?php else: ?>
            <?php foreach ($commissionsDues as $c): ?>
                <tr>
                    <td><?= esc($c['sourceLabelle']) ?></td>
                    <td><?= esc($c['destinataireLabelle']) ?></td>
                    <td><?= (int) $c['nombreTransferts'] ?></td>
                    <td><?= number_format($c['montantCommission'], 0, ',', ' ') ?> Ar</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <tr><th colspan="3">Total des commissions a reverser</th><th><?= number_format($totalCommissions, 0, ',', ' ') ?> Ar</th></tr>
        </tfoot>
    </table>
</div>
</body>
</html>