<a href="<?= $router->generate('product-list'); ?>" class="btn btn-success float-right">Retour</a>

<!-- Si un produit n'a pas d'ID c'est qu'il n'existe pas encore en BDD, c'est donc une création. Sinon, c'est qu'on modifie un produit existant -->
<?php if($product->getId() > 0): ?>
    <h2>Modifier le produit</h2>
    <?php $route = $router->generate('product-update', [ 'id' => $product->getId()]); ?>
    
<?php else: ?>
    <h2>Ajouter un produit</h2>
    <?php $route = $router->generate('product-create'); ?>
<?php endif; ?>

<form action="<?= $route ?>" method="POST" class="mt-5">
    <div class="form-group">
        <label for="name">Nom</label>
        <input value="<?= $product->getName() ?>" type="text" class="form-control" id="name" name="name" placeholder="Nom du produit">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <input value="<?= $product->getDescription() ?>"  type="text" class="form-control" id="description" name="description" placeholder="Description" aria-describedby="subtitleHelpBlock">
        <small id="subtitleHelpBlock" class="form-text text-muted">
            Description du produit
        </small>
    </div>
    <div class="form-group">
        <label for="picture">Image</label>
        <input value="<?= $product->getPicture() ?>" type="text" class="form-control" id="picture" name="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock">
        <small id="pictureHelpBlock" class="form-text text-muted">
            URL relative d'une image (jpg, gif, svg ou png)  <a href="" target="_blank"></a>
        </small>
    </div>
    <div class="form-group">
        <label for="price">Prix</label>
        <input value="<?= $product->getPrice() ?>"  type="text" class="form-control" id="price" name="price" placeholder="0.00" aria-describedby="pictureHelpBlock">
        <small id="pictureHelpBlock" class="form-text text-muted">
            le prix est à indiquer en euros au format décimal
        </small>
    </div>

    <!-- Pour les champs de type SELECT, on doit appliquer un attribut "selected" sur l'option choisie (si on est dans un formulaire d'édition). Par exemple <option value="1" selected>.
    Au sein d'une boucle, on va donc créer une variable contenant "selected" si on est sur la valeur sélectionnée, et ne contenant rien dans le cas contraire. Puis on affiche cette variable dans l'option. Si on est dans le cas d'une option non sélectionnée, ça ne changera rien dans notre code HTML, sinon, ça ajoutera l'attribut selected.
-->
    
    <div class="form-group">
        <label for="status">Disponibilité</label>
        <select class="form-control" id="status" name="status" aria-describedby="pictureHelpBlock">
            <!-- Vu qu'on n'a que deux options, on fait une condition ternaire sur chacune pour savoir si on doit afficher l'attribut selected -->
            <option value='1' <?= ($product->getStatus() == 1) ? ' selected' :'' ?>>Disponible</option>
            <option value='2' <?= ($product->getStatus() == 2) ? ' selected' :'' ?>>Indisponible</option>
        </select>
        <small id="pictureHelpBlock" class="form-text text-muted">
            Disponibilité du produit
        </small>
    </div>
    <div class="form-group">
        <label for="categoryId">Catégorie</label>
        <select class="form-control" id="categoryId" name="categoryId" aria-describedby="pictureHelpBlock">
            <?php foreach($categories as $currentCategory) : ?>

                <?php $selected = ($currentCategory->getId() == $product->getCategoryId()) ? 'selected' : ''; ?>

                <option value='<?= $currentCategory->getId() ; ?>' <?= $selected ?>><?= $currentCategory->getName() ;?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="typeId">Type</label>
        <select class="form-control" id="typeId" name="typeId" aria-describedby="pictureHelpBlock">
            <?php foreach($types as $currentType) : ?>

                <?php $selected = ($currentType->getId() == $product->getTypeId()) ? 'selected' : ''; ?>

                <option <?= $selected ?> value='<?= $currentType->getId() ; ?>'><?= $currentType->getName() ;?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
</form>