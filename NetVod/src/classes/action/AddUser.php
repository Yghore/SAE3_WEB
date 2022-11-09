<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\exception\auth\AuthException;
use iutnc\netvod\model\User;
use iutnc\netvod\render\auth\RegisterRenderer;


class AddUser extends Action
{
    /**
     * Méthode qui permet d'afficher le formulaire d'ajout d'un utilisateur
     * @return string
     */
    protected function executeGET(): string
    {
        $rr = new RegisterRenderer();

        return $rr->render();
    }

    /**
     * Méthode qui permet d'ajouter un utilisateur
     * @return string
     */
    protected function executePOST(): string
    {
        try {
            if (!User::existFromDatabase($_POST['email'], $_POST['password'])) {
                if ($_POST['password'] == $_POST['confirmer']) {
                    $token = Auth::register($_POST['email'], $_POST['password']);

                    //header('Location: ' . $_SERVER['PHP_SELF']);
                    return <<<EOF
                        <div class="bg-form">
                            <div class="form">
                                <h1>Utilisateur ajouté</h1><p>Merci de valider votre email en cliquant <a href="{$token->getValidateURL()}">ici</a></p>
                            </div>
                        </div>
                       EOF;
                } else {
                    return (new RegisterRenderer("Les mots de passe ne correspond pas"))->render();
                }
            }else{

                return (new RegisterRenderer("Cet utilisateur existe déjà"))->render();
            }
        } catch (AuthException $e) {
            return $e->getMessage();
        }
    }


}