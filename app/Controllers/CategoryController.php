<?php

namespace App\Controllers;

// Si j'ai besoin du Model Category


use App\Models\Category;


class CategoryController extends CoreController {

    /**
     * Méthode s'occupant de la liste des catégories
     *
     * @return void
     */
    public function list()
    {

        // On récupère toutes les catégories depuis la BDD en se servant de la méthode statique findAll
        $categories = Category::findAll();

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('category/list', ['categories' => $categories]);
    }

    /**
     * Méthode affichant le formulaire d'ajout de catégories
     *
     * @return void
     */
    public function add()
    {
        
         $this->generateCSRFToken();

        // On instancie une catégorie vide afin de tromper notre template. On envoie une catégorie vide à celui-ci et il va afficher ses propriétés dans les champs. Comme elles sont toutes vides, ça n'affichera rien et les champs restent vides.
        $category = new Category; 

        $this->show('category/form', ['category' => $category]);
    }

    /**
     * Methode gérant l'ajout d'une nouvelle catégorie à la bdd
     *
     * @return void
     */
    public function create()
    {
        $router = $this->router;

        $this->generateCSRFToken();

        // On récupère les données provenant du formulaire et on les filtres

        $name = filter_input(INPUT_POST, htmlspecialchars('category-name'));
        $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_URL);
        
       

        // On crée une nouvelle catégorie en instanciant le model Category

        $newCategory = new Category();

        // On attribue les valeurs à cette nouvelle catégorie

        $newCategory->setName($name);
        $newCategory->setPicture($picture);
        //dump($newCategory);

        // On insère les donées en BDD // ici c'est la method save qui est appellée car elle gère et la methode insert() et la methode update()
        if($newCategory->save()){
            $url = $router->generate('category-list');
            $_SESSION['flash-message'][] = "Une nouvelle catégorie à été créée";
            header('Location: ' .$url);
        };
    }
      /**
     * Méthode affichant le formulaire d'édition de catégories
     *
     * @return void
     */
    public function updatePage($categoryId)
    {
        $router = $this->router;
        $this->generateCSRFToken();
        // On va chercher dans la BDD la catégorie dont l'id est transmis en paramètre dynamique de l'URL
        $category = Category::find($categoryId);
        
        // On appelle la vue contenant le formulaire en lui transmettant notre catégorie trouvée.
        $this->show('category/form', ['category' => $category]);
    }

    /**
     * Méthode gérant l'ajout d'une nouvelle catégorie à la BDD
     *
     * @return void
     */
   

    public function update($categoryId)
    {
        $router = $this->router;
        $this->generateCSRFToken();
        // On récupère les données provenant du formulaire et on les filtre
        $name = filter_input(INPUT_POST, htmlspecialchars('category-name'));
        
        $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_URL);
        $home_order = filter_input(INPUT_POST, 'OrderHome', FILTER_SANITIZE_NUMBER_INT);
       
       
        // On charge l'objet depuis la BDD en utilisant la méthode find et son ID
        $category = Category::find($categoryId);

        // On modifie l'objet chargé avec les valeurs récupérées de nos champs
        $category->setName($name);
        $category->setPicture($picture);
        //$category->setHomeOrder($home_order);

        // On met à jour les données en BDD
        if($category->save()) {
            // On génère l'url de retour (page liste des catégories)
            $url = $router->generate('category-list');
            // On redirige l'utilisateur vers cette page
            $_SESSION['flash-message'][] ='Mise à jour effectuée';
            header('Location: '.$url);
        }
    }

    /**
     * Méthode supprimant la catégorie dont l'id est passé en url
     *
     * @param int $categoryId
     * @return void
     */
    public function delete($categoryId)
    {
        $router = $this->router; 
        $this->generateCSRFToken();
        // On charge la catégorie à supprimer
        $category = Category::find($categoryId);

        // On exécute la méthode de suppression et si ça a marché (true) alors on redirige vers la liste des catégories.
        if($category->delete()) {
            // On génère l'url de retour (page liste des catégories)
            $url = $router->generate('category-list');
            // On redirige l'utilisateur vers cette page
            $_SESSION['flash-message'][] = 'Catégorie effacée';
            header('Location: '.$url);
        }
    }

    public function manageHome()
    {

        $this->generateCSRFToken();

        $categories = Category::findAll();

        $this->show('category/home-form', ['categories' => $categories]);
    }

    
}

