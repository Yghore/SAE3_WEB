<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\exception\auth\AuthException;

use iutnc\netvod\exception\auth\LoginInvalidEmailException;
use iutnc\netvod\exception\auth\LoginInvalidPasswordException;
use iutnc\netvod\exception\auth\LoginInvalidUserException;
use iutnc\netvod\model\User;

class Signin extends Action
{

    protected function executeGET(): string
    {
        try
        {
            $user = User::getFromSession();
            header('location: ?action=profil');
            die();


        }
        catch(AuthException)
        {
            return <<<EOF
                <div class="bg-form">
                    <div class="form">
                        <h1>Connexion</h1>
                        <form action="?action=signin" method="post">
                            <label for="password">Email</label>
                            <input type="email" name="email" id="email" placeholder="Email" required>
                            <label for="password">Mot de passe</label>

                            <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                            
                            <input type="submit" value="Connexion">
                        </form>
                    </div>
                </div>
            EOF;

        }
    }

    protected function executePOST(): string
    {
        $content = <<<EOF
            <div class="bg-form">
                    <div class="form">
                        <h1>Connexion</h1>
                        <form action="?action=signin" method="post">
                            <label for="password">Email</label>
                            <input type="email" name="email" id="email" placeholder="Email" required>
                            <label for="password">Mot de passe</label>

                            <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                            
                            <input type="submit" value="Connexion">
                            <p class="error-text">

                
            EOF;
        try {
            if (Auth::authenticate($_POST['email'], $_POST['password'])) {
                $user = User::getFromEmail($_POST['email']);
                if ($user) {
                    header('location: ?action=home');
                    die();

                };
            }
        } catch (LoginInvalidEmailException $e) {
            $content .= "Email invalide";
        }
        catch(LoginInvalidPasswordException $e)
        {
             $content .= "Mot de passe invalide";
        }
        catch(LoginInvalidUserException $e)
        {
            $content .= "Merci de valider votre inscriptions avant de vous connecter";
        }

        $content .= "</form></div></div>";
        return $content;

    }
}