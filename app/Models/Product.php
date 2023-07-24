<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

/**
 * Une instance de Product = un produit dans la base de données
 * Product hérite de CoreModel
 */
class Product extends CoreModel {
    
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var float
     */
    private $price;
    
    /**
     * @var int
     */
    private $status;
   
    /**
     * @var int
     */
    private $category_id;
    /**
     * @var int
     */
    private $type_id;
    
    /**
     * Méthode permettant de récupérer un enregistrement de la table Product en fonction d'un id donné
     * 
     * @param int $productId ID du produit
     * @return Product
     */
    public static function find($productId)
    {
        // récupérer un objet PDO = connexion à la BDD
        $pdo = Database::getPDO();

        // on écrit la requête SQL pour récupérer le produit
        $sql = '
            SELECT *
            FROM product
            WHERE id = ' . $productId;

        // query ? exec ?
        // On fait de la LECTURE = une récupration => query()
        // si on avait fait une modification, suppression, ou un ajout => exec
        $pdoStatement = $pdo->query($sql);

        // fetchObject() pour récupérer un seul résultat
        // si j'en avais eu plusieurs => fetchAll
        $result = $pdoStatement->fetchObject(self::class);
        
        return $result;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table product
     * 
     * @return Product[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `product`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        
        return $results;
    }

    /**
     * Récupère les trois derniers produits entrés dans la BDD
     *
     * @return array
     */
    public static function findLast3()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * from product
                ORDER BY id DESC
                LIMIT 3';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        
        return $results;
    }

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
     * Get the value of description
     *
     * @return  string
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Get the value of picture
     *
     * @return  string
     */ 
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     *
     * @param  string  $picture
     */ 
    public function setPicture(string $picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of price
     *
     * @return  float
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @param  float  $price
     */ 
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    
   
    /**
     * Get the value of status
     *
     * @return  int
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  int  $status
     */ 
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

   
    

    /**
     * Get the value of category_id
     *
     * @return  int
     */ 
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set the value of category_id
     *
     * @param  int  $category_id
     */ 
    public function setCategoryId(int $category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * Get the value of type_id
     *
     * @return  int
     */ 
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Set the value of type_id
     *
     * @param  int  $type_id
     */ 
    public function setTypeId(int $type_id)
    {
        $this->type_id = $type_id;
    }

    public function insert()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO mais en mettant des "emplacements" (marqueurs) qui vont servir à indiquer les endroits contenant des données dynamiques
        $sql = "
            INSERT INTO `product` (name, description, picture, price, , status,  type_id, category_id)
            VALUES (:name, :description, :picture, :price,  :status,  :type, :category)
        ";

        // On demande à PDO de préparer le requete. C'est à dire de connaitre la structure de celle-ci, sans connaitre les valeurs.
        $query = $pdo->prepare($sql);

        // On vient attribuer à chaque marqueur une valeur dynamique. Grace au 3ème argument, on peut indiquer à PDO quel type de donnée on lui envoie. Ainsi il n'interpretera plus des strings comme étant des requetes SQL.
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':picture', $this->picture, PDO::PARAM_STR);
        $query->bindValue(':price', $this->price, PDO::PARAM_INT);
        $query->bindValue(':status', $this->status, PDO::PARAM_INT);  
        $query->bindValue(':type', $this->type_id, PDO::PARAM_INT);
        $query->bindValue(':category', $this->category_id, PDO::PARAM_INT);


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

    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            UPDATE `product`
            SET
                `name` = :name,
                `description` = :description,
                `picture` = :picture,
                `price` = :price,
                `status` = :status,
                `type_id` = :type,
                `category_id` = :category,
                `updated_at` = NOW()
            WHERE id = :id
        ";

        $query = $pdo->prepare($sql);

        // On remplace les tokens par leurs vraies valeurs
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':picture', $this->picture, PDO::PARAM_STR);
        $query->bindValue(':price', $this->price, PDO::PARAM_INT); 
        $query->bindValue(':status', $this->status, PDO::PARAM_INT);
        $query->bindValue(':type', $this->type_id, PDO::PARAM_INT);
        $query->bindValue(':category', $this->category_id, PDO::PARAM_INT);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);

        $query->execute();

        // On retourne VRAI, si au moins une ligne ajoutée
        return ($query->rowCount() > 0);
          
    }

    public function delete()
    {
       // Récupération de l'objet PDO représentant la connexion à la DB
       $pdo = Database::getPDO();

       // Requete permettant de supprimer une entrée de la table product si l'id est celui de l'objet courant
       $sql = 'DELETE FROM `product`
               WHERE id = :id';

       $query = $pdo->prepare($sql);

       $query->bindValue(':id', $this->id, PDO::PARAM_INT);

       $query->execute();

       // On retourne VRAI, si au moins une ligne ajoutée
       return ($query->rowCount() > 0);
    }

   
}
