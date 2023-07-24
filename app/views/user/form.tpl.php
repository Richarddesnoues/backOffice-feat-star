<div class="container my-4">
    <a href="<?= $router->generate('user-list') ?>" class="btn btn-success float-right">Retour</a>
    <h2>Ajouter un utilisateur</h2>
    <form action="<?= $router->generate('user-add') ?>" method="POST" class="mt-5">
    
        <div class="form-group">
            <label for="email">Email</label>
            <input value="<?= $user->getEmail() ?>" type="email" class="form-control" id="email" name="email" placeholder="Votre email">
        </div>

        <div class="form-group">
            <label for="pass">Password</label>
            <input  value="<?= $user->getPassword() ?>"  type="password" class="form-control" id="pass" name="password" placeholder="Votre mot de passe" aria-describedby="subtitleHelpBlock">
        </div>

        <div class="form-group">
            <label for="pass-confirm">Password confirm</label>
            <input value="<?= $user->getPassword() ?>"  type="password" class="form-control" id="pass-confirm" name="password-confirm" placeholder="Confirmez le mot de passe" aria-describedby="subtitleHelpBlock">
        </div>

        <div class="form-group">
            <label for="Pseudo">Pseudo</label>
            <input value="<?= $user->getPseudo() ?>"  type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo" aria-describedby="pictureHelpBlock">
        </div>

        <div class="form-group">
            <label for="role-select">Choisissez un role:</label>
            <select name="role" id="role-select" class="form-control">
                <option value="">--Votre role--</option>
                <option value="admin" <?= ($user->getRole() == 'admin') ? 'selected' : '' ?>>Admin</option>
                <option value="catalog-manager" <?= ($user->getRole() == 'catalog-manager') ? 'selected' : '' ?>>Catalog manager</option>
            </select>
        </div>
        <!-- Lorsqu'on charge cette page, un token est généré et stocké en session. On peut donc le récupérer et le mettre dans un champ caché -->
        <input type="hidden"  name="token" value="<?= $_SESSION['csrfToken'] ?>">
        <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>    
    </form>
</div>