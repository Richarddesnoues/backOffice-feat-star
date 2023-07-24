<?php

namespace App\Controllers;

class CoreController {

    public $router;

    public function __construct($routeName, $router)
    {
        $this-> router = $router;
        //On liste toutes les routes qui ont besoin d'une protection
        $acl = [
    
            'main-home' => ['admin', 'catalog-manager'],
            'user-add' => ['admin'],
            'user-list'=> ['admin'],
            'user-addPage' => ['admin'],
            'product-list' => ['admin', 'catalog-manager'],
            'category-create' => ['admin', 'catalog-manager'],
            'product-create' => ['admin', 'catalog-manager'],
            'product-update' => ['admin', 'catalog-manager'],
            'category-update' => ['admin'],
            'category-delete' => ['admin'],
            'product-delete' => ['admin', 'catalog-manager'],
            'product-updatepage' => ['admin', 'catalog-manager']
        ];
    
        // On vérifie que la route actuelle fait partie des routes sur lesquelles on a mis en place une sécurité
        // on appelle la méthode qui vérifie la présence et la validité du token.
        if(array_key_exists($routeName, $acl)) {
            $this->checkAuthorization($acl[$routeName]);
        }

        $protectedRoutes = [
            'category-create',
            'product-create',
            'product-update' ,
            'category-update' ,
            'category-delete' ,
            'product-delete' ,     
        ];
    
        // Si la route actuelle fait partie des routes protégées
        // on appelle la méthode qui vérifie la présence et la validité du token.
        if(in_array($routeName, $protectedRoutes)) {
            $this->checkCSRFToken($protectedRoutes[$routeName]);
        }
    }
   
    /**
     * Vérifie que le token POST est présent et correspond au token stocké en session
     *
     * @return void
     */
    public function checkCSRFToken()
    {
        // Voici les cas de figure où le formulaire ne sera pas soumis : 
        // - Pas de token dans $_POST
        // - Pas de token dans la session
        // - Le token de post et le token de la session sont différents

        // On récupère le token de $_POST
        $postToken = filter_input(INPUT_POST, 'token');
        // On récupère le token de la session
        $sessionToken = $_SESSION['csrfToken'];
        
        // On vérifie que les tokens existent et sont bien identiques.
        if(empty($postToken) || empty($sessionToken) || $postToken != $sessionToken) {
            http_response_code(403);
            $this->show('error/err403');
            exit();
        }
    }
 
    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewVars Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewVars = []) {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;

        // Comme $viewVars est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewVars['currentPage'] = $viewName; 

        // définir l'url absolue pour nos assets
        $viewVars['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewVars['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewVars, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewVars);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewVars est disponible dans chaque fichier de vue
        require_once __DIR__.'/../views/layout/header.tpl.php';
        require_once __DIR__.'/../views/'.$viewName.'.tpl.php';
        require_once __DIR__.'/../views/layout/footer.tpl.php';
    }

    /**
     * Méthode permettant de définir si l'utilisateur connecté peut accéder à une page. On lui passe un tableau qui contient la liste roles autorisés sur cette page.
     *
     * @param array $authorizedRoles
     * @return void
     */
    public function checkAuthorization($authorizedRoles = [])
    {
        global $router;
        // On vérifie que l'utilisateur est connecté
        if(isset($_SESSION['userId'])) {
            // Si oui, on récupère son role
            $connectedUser = $_SESSION['connectedUser'];
            $role = $connectedUser->getRole();
            
            // Si le role fait partie des roles autorisés (fournis dans le paramètre $role) on retourne true
            if(in_array($role, $authorizedRoles)) {
                return true;
            }
            // Si non, l'utilisateur n'est pas autorisé, on va lui envoyer une erreur : erreur 403 forbidden
            else {
                http_response_code(403);

                $this->show('error/err403');
                exit();
            }
        }
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        else {
            $pageLogin = $router->generate('user-loginPage');
            header('Location: '.$pageLogin);
        }
    }

    /**
     * Génère un token CSRF, le stocke en Session et le retourne
     *
     * @return string
     */
    public function generateCSRFToken()
    {
        $bytes = random_bytes(10);
        $token = bin2hex($bytes);

        $_SESSION['csrfToken'] = $token;

        return $token;
    }
}
