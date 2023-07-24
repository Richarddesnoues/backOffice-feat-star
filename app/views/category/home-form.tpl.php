<form action="<?= $router->generate('category-saveHome') ?>" method="POST" class="mt-5">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="emplacement1">Emplacement #1</label>
                <select class="form-control" id="emplacement1" name="emplacement[]">
                    <option value="">choisissez :</option>
                    <?php foreach($categories as $currentCategory): ?>
                        <option value="<?= $currentCategory->getId() ?>"><?= $currentCategory->getName() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="emplacement2">Emplacement #2</label>
                <select class="form-control" id="emplacement2" name="emplacement[]">
                    <option value="">choisissez :</option>
                    <?php foreach($categories as $currentCategory): ?>
                        <option value="<?= $currentCategory->getId() ?>"><?= $currentCategory->getName() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="emplacement3">Emplacement #3</label>
                <select class="form-control" id="emplacement3" name="emplacement[]">
                    <option value="">choisissez :</option>
                    <?php foreach($categories as $currentCategory): ?>
                        <option value="<?= $currentCategory->getId() ?>"><?= $currentCategory->getName() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="emplacement4">Emplacement #4</label>
                <select class="form-control" id="emplacement4" name="emplacement[]">
                    <option value="">choisissez :</option>
                    <?php foreach($categories as $currentCategory): ?>
                        <option value="<?= $currentCategory->getId() ?>"><?= $currentCategory->getName() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
    <input type="hidden" name="token" value="<?= $_SESSION['csrfToken']?>">
    <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
</form>