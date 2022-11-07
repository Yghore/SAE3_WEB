<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\exception\auth\AuthException;


class AddUser extends Action
{
    /**
     * Méthode qui permet d'afficher le formulaire d'ajout d'un utilisateur
     * @return string
     */
    protected function executeGET(): string
    {
        $content = '<h1>Ajout d’un utilisateur</h1>';
        $content .= '<form action="index.php?action=add-user" method="post">';
        $content .= '<label for="email">Email</label>';
        $content .= '<input type="email" name="email" id="email" required>';
        $content .= '<label for="password">Mot de passe</label>';
        $content .= '<input type="password" name="password" id="password" required>';
        $content .= '<label for="password">Confirmer</label>';
        $content .= '<input type="password" name="confirmer" id="confirmer" required>';
        $content .= '<input type="submit" value="Ajouter">';
        $content .= '</form>';

        return $content;
    }

    /**
     * Méthode qui permet d'ajouter un utilisateur
     * @return string
     */
    protected function executePOST(): string
    {
        try {
            Auth::register($_POST['email'], $_POST['password']);
            header('Location: '.$_SERVER['PHP_SELF']);
            return 'Utilisateur inscrit';
        } catch (AuthException $e) {
            return print($e->getMessage());
        }

    }


}