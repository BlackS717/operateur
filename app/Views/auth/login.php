<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<div class="auth-shell">
    <div class="auth-card">
        <h1>Mobile Money</h1>
        <p class="auth-subtitle">Connectez-vous avec votre numero de telephone.</p>

        <?php if ($reports = session()->getFlashdata('reports')) { ?>
            <div class="alert alert-danger">
                <?php foreach ($reports as $report) { ?>
                    <p class="mb-0"><?= esc($report) ?></p>
                <?php } ?>
            </div>
        <?php } ?>

        <form action="<?= base_url('/auth/login') ?>" method="post">
            <div class="mb-3">
                <label for="phone" class="form-label">Numero de telephone</label>
                <input type="number" id="phone" name="phone" class="form-control" inputmode="numeric" pattern="[0-9]" required value="<?= old('phone') ?>">
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>

        <div class="auth-switch">
            Vous etes operateur ? <a href="<?= base_url('/admin/login') ?>">Connexion admin</a>
        </div>
    </div>
</div>
</body>
</html>
