<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion operateur</title>
    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<div class="auth-shell">
    <div class="auth-card">
        <h1>Espace operateur</h1>
        <p class="auth-subtitle">Connectez-vous avec les identifiants de votre operateur.</p>

        <?php if ($reports = session()->getFlashdata('reports')) { ?>
            <div class="alert alert-danger">
                <?php foreach ($reports as $report) { ?>
                    <p class="mb-0"><?= esc($report) ?></p>
                <?php } ?>
            </div>
        <?php } ?>

        <form action="<?= base_url('/admin/login') ?>" method="post">
            <div class="mb-3">
                <label for="labelle" class="form-label">Operateur</label>
                <input type="text" id="labelle" name="labelle" class="form-control" required value="MVola">
            </div>
            <div class="mb-3">
                <label for="motDePasse" class="form-label">Mot de passe</label>
                <input type="password" id="motDePasse" name="motDePasse" value="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Se connecter</button>
        </form>

        <div class="auth-switch">
            Vous etes client ? <a href="<?= base_url('/') ?>">Connexion</a>
        </div>
    </div>
</div>
</body>
</html>
