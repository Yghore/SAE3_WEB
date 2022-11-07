<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\exception\AuthException;
use iutnc\netvod\model\User;

class Signin extends Action
{

    protected function executeGET(): string
    {
        $content = '<h1>Connexion</h1>';
        $content .= '<form action="index.php?action=add-user" method="post">';
        $content .= '<label for="email">Email</label>';
        $content .= '<input type="email" name="email" id="email" required>';
        $content .= '<label for="password">Mot de passe</label>';
        $content .= '<input type="password" name="password" id="password" required>';
        $content .= '<input type="submit" value="Connexion">';
        $content .= '</form>';

        return $content;
    }

    protected function executePOST(): string
    {
        $content = '';
        try {
            if (isset($_SESSION['user']) or Auth::authenticate($_POST['email'], $_POST['password'])) {
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