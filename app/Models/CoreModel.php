<?php

namespace App\Models;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models

// Une classe abstraite ne peut pas etre instanciée, elle sert juste de base à des classes enfant.
// Elle permet aussi de définir les propriétés et méthodes que ses enfants devront OBLIGATOIREMENT implémenter.

abstract class CoreModel {
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var string
     */
    protected $updated_at;


    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * Get the value of created_at
     *
     * @return  string
     */ 
    public function getCreatedAt() : string
    {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at
     *
     * @return  string
     */ 
    public function getUpdatedAt() : string
    {
        return $this->updated_at;
    }

    /**
     * Méthode permettant de sauvegarder une entité dans la BDD. Elle fait le choix d'ajouter ou de modifier une entrée de la BDD selon si c'est une entité qui existe déjà ou non.
     *
     * @return bool
     */
    public function save()
    {
        // On vérifie que notre entité existe déjà, c'est à dire qu'elle a un ID non nul.
        // Si c'est le cas on met à jour l'entrée dans la BDD
        if($this->id > 0) {
            // On appelle la méthode update (qui est obligatoirement implémentée par le model enfant car abstraite), elle nous renvoie une valeur booléenne (true si la mise à marché). 
            $result = $this->update();
        // Sinon on ajoute une une entrée dans la BDD
        } else {
            // Sur le meme principe, on va insérer une nouvelle entrée dans la BDD.
            $result = $this->insert();
        }

        // $result contient true si l'insertion ou mise à jour à marché. False, si ça n'a pas marché. On renvoie donc cette valeur pour indiquer le bon déroulement de la requete.
        return $result;
    }

    // En créant des méthodes abstraites, on oblige les enfants de coreModel à implémenter ces méthodes. Ne pas le faire génère une erreur et bloque notre site.
    // On peut décrire le type de méthode directement dans coreModel : statique ? publique ? privée ? 
    abstract static public function find($id);
    abstract static public function findAll();
    abstract public function insert();
    abstract public function update();
    abstract public function delete();

}
