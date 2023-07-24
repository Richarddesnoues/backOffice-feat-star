<?php

namespace App\Models;

use App\Models\CoreModel;
use App\Utils\Database;
use PDO;

class AppUser extends CoreModel {


    

    private $pseudo;


    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    private $role;

   

   

    /**
     * Get the value of email
     *
     * @return  string
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole(string $role)
    {
        $this->role = $role;

        return $this;
    }


    /**
     * Get the value of password
     *
     * @return  string
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param  string  $password
     */ 
    public function setPassword(string $password)
    {
        $this->password = $password;
    }


    /**
     * Get the value of pseudo
     */ 
    public function getPseudo()
    {
        return $this->pseudo;
    }

    
    /**
     * Set the value of pseudo
     *
     * @return  self
     */ 
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }
    
    

        
    public static function find($userId)
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT * 
            FROM `user`
            WHERE id = '.$userId;

  // On fait de la LECTURE = une récupration => query()
        // si on avait fait une modification, suppression, ou un ajout => exec
        $pdoStatement = $pdo->query($sql);

        // fetchObject() pour récupérer un seul résultat
        // si j'en avais eu plusieurs => fetchAll
        $result = $pdoStatement->fetchObject(self::class);
        
        return $result;
    
    }

    /**
     * Méthode permettant de trouver tous les utilisateurs dans la BDD
     *
     * @return array
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `user`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        
        return $results;
    }

    public function insert()
    {
       // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO mais en mettant des "emplacements" (marqueurs) qui vont servir à indiquer les endroits contenant des données dynamiques
        
        $sql = "
        INSERT INTO `user` (pseudo, email, password, role)
        VALUES (:pseudo, :email, :password, :role)";
    

        // On demande à PDO de préparer le requete. C'est à dire de connaitre la structure de celle-ci, sans connaitre les valeurs.
        $query = $pdo->prepare($sql);
        
        // On vient attribuer à chaque marqueur une valeur dynamique. Grace au 3ème argument, on peut indiquer à PDO quel type de donnée on lui envoie. Ainsi il n'interpretera plus des strings comme étant des requetes SQL.
        $query->bindValue(':pseudo', $this->pseudo, PDO::PARAM_STR);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':password', $this->password, PDO::PARAM_STR);
        $query->bindValue(':role', $this->role, PDO::PARAM_STR);
        
        // Enfin, on dit à PDO d'exécuter notre requete
        $query->execute();

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
            UPDATE `user`
            SET
                `pseudo` = :pseudo,
                `email` = :email,
                `password` = :password,
                `role` = :role,
                
            WHERE id = :id
        ";

        $query = $pdo->prepare($sql);

        $query->bindValue(':pseudo', $this->pseudo, PDO::PARAM_STR);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':password', $this->password, PDO::PARAM_STR);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':role', $this->role, PDO::PARAM_STR);

        $query->execute();

        // On retourne VRAI, si au moins une ligne ajoutée
        return ($query->rowCount() > 0);
    }



    public function delete()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
       $pdo = Database::getPDO();

       // Requete permettant de supprimer une entrée de la table product si l'id est celui de l'objet courant
       $sql = 'DELETE FROM `user`
               WHERE id = :id';

       $query = $pdo->prepare($sql);

       $query->bindValue(':id', $this->id, PDO::PARAM_INT);

       $query->execute();

       // On retourne VRAI, si au moins une ligne ajoutée
       return ($query->rowCount() > 0);
    }




    public static function findByEmail($email)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // On construit notre requete SQL en utilisant un marqueur dynamique pour l'email
        $sql = "SELECT * FROM `user`
        WHERE `email` = :email";

        // On prépare la requete
        $query = $pdo->prepare($sql);

        // On remplace les valeurs dynamiques par notre email
        // Par défaut, bindValue utilise le filtre PARAM_STR, donc pas besoin de l'indiquer !
        $query->bindValue(':email', $email);

        // On exécute la requete
        $query->execute();

        // On prend le résultat et on instancie la classe dans laquelle on se situe.
        $user = $query->fetchObject(self::class);

        return $user;

    } 
}