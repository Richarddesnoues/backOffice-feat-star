<a href="<?= $router->generate('category-list') ?>" class="btn btn-success float-right">Retour</a>

<!-- Si une catégorie n'a pas d'ID c'est qu'elle n'existe pas encore en BDD, c'est donc une création. Sinon, c'est qu'on modifie une catégorie existante -->
<?php if($category->getId() > 0): ?>
    <h2>Modifier la catégorie</h2>
    <?php $route = $router->generate('category-update', [ 'id' => $category->getId()]); ?>
<?php else: ?>
    <h2>Ajouter une catégorie</h2>
    <?php $route = $router->generate('category-create'); ?>
<?php endif; ?>

<form action="<?= $route ?>" method="POST" class="mt-5">
    <div class="form-group">
        <label for="name">Nom</label>
        <input value="<?= $category->getName() ?>" name="category-name" type="text" class="form-control" id="name" placeholder="Nom de la catégorie">
    </div>
    
    <div class="form-group">
        <label for="picture">Image</label>
        <input value="<?= $category->getPicture() ?>" name="picture" type="text" class="form-control" id="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock">
        <small id="pictureHelpBlock" class="form-text text-muted">
            <a href="" target="_blank"></a>
        </small>
    </div>
   
     <!-- Lorsqu'on charge cette page, un token est généré et stocké en session. On peut donc le récupérer et le mettre dans un champ caché -->
     <input type="hidden"  name="token" value="<?= $_SESSION['csrfToken'] ?>">
    <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
</form>