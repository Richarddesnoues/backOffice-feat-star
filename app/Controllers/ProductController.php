<?php

namespace App\Controllers;


use App\Models\Category;
use App\Models\Product;
use App\Models\Type;


// Si j'ai besoin du Model Category
// use App\Models\Category;


class ProductController extends CoreController {

    /**
     * Méthode s'occupant de la liste des produits
     *
     * @return void
     */
    public function list()
    {
     
        $products = Product::findAll();

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('product/list', ['products' => $products]);
    }

    /**
     * Méthode affichant le formulaire d'ajout de produits
     *
     * @return void
     */
    public function add()
    {
        $router = $this->router;
        $this->generateCSRFToken();

        // on récupère toutes les categories; On prends le modèle Category et on récupère tout dans tableau
        $categories = Category::findAll();
        // on récupère tout les types
        $types = Type::findAll();

        $product = new Product();

        $this->show('product/form', [
            'categories' => $categories, // une clef qui a pour valeur un tableau avec toutes les categories
            'types' => $types,
            'product' => $product,
        ]);
    }

    public function create()
    {

        $router = $this->router; 
        $this->generateCSRFToken();
         // On récupère les données provenant du formulaire et on les filtre
         $name = filter_input(INPUT_POST,  htmlspecialchars('name'));
         $description = filter_input(INPUT_POST, htmlspecialchars('description'));
         $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_URL);
         $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT);
         $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
         $categoryId = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
         $typeId = filter_input(INPUT_POST, 'typeId', FILTER_SANITIZE_NUMBER_INT);
 
        // On crée un nouveau produit en instanciant le model Product
        $newProduct = new Product();

        // On attribue les valeurs à cette nouvelle catégorie 
        $newProduct->setName($name);
        $newProduct->setDescription($description);
        $newProduct->setPicture($picture);
        $newProduct->setPrice($price);
        $newProduct->setStatus($status);
        $newProduct->setCategoryId($categoryId);
        $newProduct->setTypeId($typeId);


        // On insère les données en BDD
        if($newProduct->save()) {
            // On génère l'url de retour (page liste des catégories)
            $url = $router->generate('product-list');
            // On redirige l'utilisateur vers cette page
            header('Location: '.$url);
        }
    }

    public function update($productId)
    {
        $router = $this->router;
        $this->generateCSRFToken();
        // On récupère les données provenant du formulaire et on les filtre
        $name = filter_input(INPUT_POST,  htmlspecialchars('name'));
        $description = filter_input(INPUT_POST, htmlspecialchars('description'));
        $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_URL);
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
        $categoryId = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
        $typeId = filter_input(INPUT_POST, 'typeId', FILTER_SANITIZE_NUMBER_INT);
 
        // On charge l'objet depuis la BDD en utilisant la méthode find et son ID
        $product = Product::find($productId);

        // On modifie l'objet chargé avec les valeurs récupérées de nos champs
        $product->setName($name);
        $product->setDescription($description);
        $product->setPicture($picture);
        $product->setPrice($price);
        $product->setStatus($status);
        $product->setCategoryId($categoryId);
       
        $product->setTypeId($typeId);

        // On met à jour les données en BDD
        if($product->save()) {
            // On génère l'url de retour (page liste des produits)
            $url = $router->generate('product-list');
            // On redirige l'utilisateur vers cette page
            header('Location: '.$url);
        }
    }

    /**
     * Méthode affichant le formulaire d'édition de produits
     *
     * @return void
     */
    public function updatePage($productId)
    {

        // On charge les catégories, marques et types pour les select
        $categories = Category::findAll();
        $types = Type::findAll();

        // On va chercher dans la BDD le produit dont l'id est transmis en paramètre dynamique de l'URL
        $product = Product::find($productId);
        
        // On crée un tableau avec les données à passer à la vue
        $dataToView = [
            'categories' => $categories,
            'types' => $types,
            'product' => $product
        ];

        // On appelle la vue contenant le formulaire en lui transmettant notre produit trouvé.
        $this->show('product/form', $dataToView);
    }

      /**
     * Méthode supprimant le produit dont l'id est passé en url
     *
     * @param int $productId
     * @return void
     */
    public function delete($productId)
    {
        $router = $this->router;
        // On charge le produit à supprimer
        $product = Product::find($productId);

        // On exécute la méthode de suppression et si ça a marché (true) alors on redirige vers la liste des produits.
        if($product->delete()) {
            // On génère l'url de retour (page liste des produits)
            $url = $router->generate('product-list');
            // On redirige l'utilisateur vers cette page
            header('Location: '.$url);
        }
    }
}
