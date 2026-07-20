<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php  if($reports = session()->getFlashdata('reports')) { ?>
        <?php foreach($reports as $report) {?>
            <p><?= esc($report) ?></p>
        <?php } ?>
    <?php }?>


    <form action="<?= base_url('/auth/login') ?>" method="post">
        <label for="username">Phone number:</label>
        <input type="number" id="phone" name="phone" required value="<?= old('phone') ?>">
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>