<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert</title>
    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<?= $this->include('partials/client_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Faire un transfert</h1>
    <form action="<?= base_url('/user/transfert') ?>" method="post" class="col-md-5">
        <div class="mb-3">
            <label for="numeros" class="form-label">Numero(s) du/des destinataire(s)</label>
            <input type="text" id="numeros" name="numeros" class="form-control" placeholder="Ex: 0348041388 ou 0348041388,0389299922" required value="<?= old('numeros') ?>">
            <div class="form-text">Pour plusieurs destinataires, separez les numeros par une virgule. Le montant sera divise equitablement.</div>
        </div>
        <div class="mb-3">
            <label for="montant" class="form-label">Montant total a transferer (Ar)</label>
            <input type="number" step="0.01" min="100" max="2000000" id="montant" name="montant" class="form-control" required value="<?= old('montant') ?>">
            <div class="form-text">Montant total a partager entre les destinataires.</div>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="inclureFrais" name="inclureFrais" value="1" <?= old('inclureFrais', '1') === '1' ? 'checked' : '' ?>>
            <label class="form-check-label" for="inclureFrais">Inclure les frais de transfert dans le montant debite</label>
            <div class="form-text">Si coche, les frais sont ajoutes au montant debite de votre compte. Le(s) destinataire(s) recoit/recoivent le montant exact.</div>
        </div>
        <button type="submit" class="btn btn-primary">Transferer</button>
    </form>
</div>
</body>
</html>