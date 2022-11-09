<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\exception\auth\AuthException;
use iutnc\netvod\model\User;


class AddUser extends Action
{
    /**
     * Méthode qui permet d'afficher le formulaire d'ajout d'un utilisateur
     * @return string
     */
    protected function executeGET(): string
    {
        $content = <<<EOF
            <div class="bg-form">
                <div class="form">
                    <h1>Ajout d’un utilisateur</h1>
                    <form action="?action=add-user" method="post">
                         <label for="email">Email</label>
                         <input type="email" name="email" id="email" required>
                         <label for="password">Mot de passe</label>
                         <input type="password" name="password" id="password" required>
                         <label for="password">Confirmer</label>
                         <input type="password" name="confirmer" id="confirmer" required>
                         <input type="submit" value="Ajouter">
                    </form>
                </div>
            </div>
         EOF;

        return $content;
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
                    Auth::register($_POST['email'], $_POST['password']);
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    return '<h1>Utilisateur ajouté</h1>';
                } else {
                    return '<h1>Les mots de passe ne correspondent pas</h1>';
                }
            }else{
                return '<h1>Cet utilisateur existe déjà</h1>';
            }
        } catch (AuthException $e) {
            return $e->getMessage();
        }
    }


}