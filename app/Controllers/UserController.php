<?php

namespace App\Controllers;

use App\Models\AppUser;

// Classe gérant les utilisateurs du back office
class UserController extends CoreController
{
    public function loginPage()
    {
        $this->show('user/login');
    }

    /**
     * Méthode gérant la soumission du formulaire
     *
     * @return void
     */
    public function login()
    {
       
        $router = $this->router;

        // On récupère les valeurs des  champs du formulaire
        $email = filter_input(INPUT_POST, htmlspecialchars('email'));
        $password = filter_input(INPUT_POST, htmlspecialchars('password'));

        // On récupère l'utilisateur d'après son adresse email
        $user = AppUser::findByEmail($email);

        // Si l'utilisateur existe (donc si $user n'est pas false)
        if ($user) {
            // On vérifie que le mot de passe de l'utilisateur correspond à celui tapé dans le champ
            // La fonction verify permet de vérifier que le mot de passe tapé dans le formulaire correspond au hash du mot de passe stocké dans la BDD.
            if (strcmp($password, $user->getPassword())) {
                // Logiquement on dois mettre password_verify à la place de strcmp, mais comme j'ai mis mon mdp en clair dans la BDD, le mot de passe , ne correspondait pas car il attandait un hash 

                // On stocke les infos de l'utilisateur dans sa session (son coffre fort dont la clé est son cookie PHPSESSID)
                $_SESSION['userId'] = $user->getId();
                $_SESSION['connectedUser'] = $user;

                // On redirige vers la page d'accueil
                header('Location: ' . $router->generate('main-home'));
            } else {
                echo "Mot de passe pas bon !";
            }
        } else {
            echo "Adresse email non trouvée !";
        }
    }

    public function logout()
    {
        $router = $this->router;
        // On supprime les entrées liées à mon utilisateur dans la session
        unset($_SESSION['userId']);
        unset($_SESSION['connectedUser']);
        $_SESSION['flash-message'][] ='Vous avez été déconecté';
        header('Location: ' . $router->generate('user-login'));
    }

    public function list()
    {
        // On récupère les utilisateurs de puis la BDD
        $users = AppUser::findAll();
        // On appelle le template qui affiche la liste en lui passant les utilisateurs.
        $this->show('user/list', ['users' => $users]);
        
    }

    public function addPage()
    {
        // On instancie un utilisateur vide afin de "remplir" les champs avec du vide
        $user = new AppUser;

        // On génère un nouveau token au chargement du formulaire. Ce dernier est stocké dans l'entrée "csrfToken" des sessions.
        $this->generateCSRFToken();
        $this->show('user/form', ['user' => $user]);
    }

    public function add()
    {   
        
        $router = $this->router;
        // On définit un tableau qui servira à stocker les erreurs.
        $errorsList = [];

        // On récupère les valeurs de nos champs
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $passwordConfirm = filter_input(INPUT_POST, 'password-confirm');
        $pseudo = filter_input(INPUT_POST, 'pseudo');
        $role =  filter_input(INPUT_POST, 'role');

        // On vérifie que tous les champs sont bien remplis en bouclant sur ceux-ci
        foreach ($_POST as $field) {
            // Si un champ est vide
            if (empty($field)) {
                // On stocke dans la liste des erreurs le message à afficher
                $errorsList[] = "Tous les champs doivent etre remplis !";
                // Puis on arrete la boucle (pas besoin de continuer, on sait qu'au moins un des champs est vide).
                break;
            }
        }

        // On vérifie que le mot de passe et sa vérification sont identiques
        if ($password != $passwordConfirm) {
            $errorsList[] = "Le mot de passe et sa confirmation ne sont pas identiques !";
        }

        // On vérifie que l'email a le bon format
        $emailValidated = filter_var($email, FILTER_VALIDATE_EMAIL);

        // Si l'email n'est pas valide, alors on stocke un nouveau message d'erreur.
        if (!$emailValidated) {
            $errorsList[] = "L'adresse email n'est pas valide !";
        }

        $newUser = new AppUser();
        $newUser->setEmail($email);
        $newUser->setPassword($password);
        $newUser->setPseudo($pseudo);
        $newUser->setRole($role);

        // On vérifie que le tableau d'erreurs est vide
        if (empty($errorsList)) {
            // On procède au hash du mot de passe
            $hashedPassword = password_hash($newUser->getPassword(), PASSWORD_DEFAULT);
            // Puis on sauvegarde le mot de passe hashé dans notre propriété Password
            $newUser->setPassword($hashedPassword);
            // On peut sauvegarder l'utilisateur.
            if ($newUser->save()) {
                // On génère l'url de retour (page liste des utilisateurs)
                $url = $router->generate('user-list');
                // On redirige l'utilisateur vers cette page
                $_SESSION['flash-message'][] ='Un nouvel utilisateur a été créé';
                header('Location: ' . $url);
            }
        } else {
            // On raffiche le formulaire en lui passant notre tableau d'erreurs.
            $this->show('user/form', ['errorsList' => $errorsList, 'user' => $newUser]);
        }
    }

    public function delete($userId)
    {
        $router = $this->router;
        // On charge l'utilisateur à supprimer
        $user = AppUser::find($userId);
        
        // On exécute la méthode de suppression et si ça a marché (true) alors on redirige vers la liste des utilisateur.
        if ($user->delete()) {
            // On génère l'url de retour (page liste des utilisateurs)
            $url = $router->generate('user-list');
            // On redirige l'utilisateur vers cette page
            $_SESSION['flash-message'][] ='Un utilisateur a été effacé';
            header('Location: ' . $url);
        }
    }
   
}
