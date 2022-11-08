<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\exception\AuthException;
use iutnc\netvod\model\User;

class Signin extends Action
{

    protected function executeGET(): string
    {
        try
        {
            $user = User::getFromSession();
            $content = <<<EOF
                <h1>Vous êtes déjà connecté :</h1>
                <div>Utilisateur : <bold>{$user->email}</bold></div>
            EOF;

        }
        catch(\iutnc\netvod\exception\auth\AuthException)
        {
            $content = <<<EOF
            <h1>Connexion</h1>
            <form action="index.php?action=signin" method="post">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Connexion">
            </form>
            EOF;
        }
        return $content;
    }

    protected function executePOST(): string
    {
        $content = '';
        try {
            if (Auth::authenticate($_POST['email'], $_POST['password'])) {
                $user = User::getFromEmail($_POST['email']);
                if (!$user) {
                    $user = $_SESSION['user'];
                };
            }
            $content .= '</div>';
        } catch (AuthException $e) {
            print($e->getMessage());
        }

        return $content;
    }
}