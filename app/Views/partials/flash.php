<?php if ($reports = session()->getFlashdata('reports')): ?>
    <div class="alert alert-info">
        <ul class="mb-0">
            <?php foreach ($reports as $report): ?>
                <li><?= esc($report) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
