<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Category extends CoreModel {

    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $picture;
   
    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     */ 
    public function setName(string $name)
    {
        $this->name = $name;
    }

    

    /**
     * Get the value of picture
     */ 
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     */ 
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /** TODO
     * Get the value of home_order
     */ 
    // public function getHomeOrder()
    // {
    //     return $this->home_order;
    // }

    // /**
    //  * Set the value of home_order
    //  */ 
    // public function setHomeOrder($home_order)
    // {
    //     $this->home_order = $home_order;
    // }

    /**
     * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
     * 
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function find($categoryId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `category` WHERE `id` =' . $categoryId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $category = $pdoStatement->fetchObject('App\Models\Category');

        // retourner le résultat
        return $category;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table category
     * 
     * @return Category[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `category`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');
        
        return $results;
    }

    /**
     * Récupérer les 5 catégories mises en avant sur la home
     * 
     * @return Category[]
     */
    public function findAllHomepage()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM category
            WHERE home_order > 0
            ORDER BY home_order ASC
        ';
        $pdoStatement = $pdo->query($sql);
        $categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');
        
        return $categories;
    }


    /**
     * Récupère les trois dernières catégories entrées dans la BDD
     *
     * @return array
     */
    public static function findLast3()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * from category
                ORDER BY id DESC
                LIMIT 3';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        
        return $results;
    }



    
    public  function insert()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO mais en mettant des "emplacements" (marqueurs) qui vont servir à indiquer les endroits contenant des données dynamiques
        $sql = "
            INSERT INTO `category` (name, picture )
            VALUES (:name,  :picture )
        ";

        // On demande à PDO de préparer le requete. C'est à dire de connaitre la structure de celle-ci, sans connaitre les valeurs.
        $query = $pdo->prepare($sql);

        // On vient attribuer à chaque marqueur une valeur dynamique. Grace au 3ème argument, on peut indiquer à PDO quel type de donnée on lui envoie. Ainsi il n'interpretera plus des strings comme étant des requetes SQL.
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':picture', $this->picture, PDO::PARAM_STR);
       

        // Enfin, on dit à PDO d'exécuter notre requete
        $query->execute();

        // Execution de la requête d'insertion (exec, pas query)
        // $insertedRows = $pdo->exec($sql);

        // Si au moins une ligne ajoutée
        if ($query->rowCount() > 0) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();

            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }
        
        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }

      /**
     * Méthode permettant de mettre à jour un enregistrement dans la table category
     * L'objet courant doit contenir l'id, et toutes les données à ajouter : 1 propriété => 1 colonne dans la table
     * 
     * @return bool
     */
    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            UPDATE `category`
            SET
                `name` = :name,
                `picture` = :picture,
                `updated_at` = NOW()
            WHERE id = :id
        ";

        $query = $pdo->prepare($sql);

        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':picture', $this->picture, PDO::PARAM_STR);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);

        $query->execute();

        // On retourne VRAI, si au moins une ligne ajoutée
        return ($query->rowCount() > 0);
        
        
    }

    





    public function delete()
    {
         // Récupération de l'objet PDO représentant la connexion à la DB
         $pdo = Database::getPDO();

        // Requete permettant de supprimer une entrée de la table category si l'id est celui de l'objet courant
        $sql = 'DELETE FROM `category`
                WHERE id = :id';

        $query = $pdo->prepare($sql);

        $query->bindValue(':id', $this->id, PDO::PARAM_INT);

        $query->execute();

        // On retourne VRAI, si au moins une ligne ajoutée
        return ($query->rowCount() > 0);
        
    }

    /**
     * Remet à zéro le champ home_order de toutes les catégories
     *
     * @return int
     */
    public static function resetHomeOrder()
    {

        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();


        // On met à zéro toutes les catégories dont le champ home_order est différent de 0. 
        $sql = "UPDATE `category`
        SET `home_order` = 0
        WHERE home_order > 0";
        
        // On exécute la requete et on renvoie le nombre de lignes modifiées
        return $pdo->exec($sql);

    }

}
