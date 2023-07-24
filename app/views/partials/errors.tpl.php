<?php if(isset($errorsList) && !empty($errorsList)): ?>
    <?php foreach ($errorsList as $currentError): ?>
        <div class="alert alert-danger" role="alert">
            <?= $currentError ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>