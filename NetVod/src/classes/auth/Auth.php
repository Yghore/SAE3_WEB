<?php

namespace iutnc\netvod\auth;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\netvod\exception\AuthException;
use iutnc\netvod\exception\LoginInvalidEmailException;
use iutnc\netvod\exception\LoginInvalidPasswordException;
use iutnc\netvod\model\User;

class Auth
{

    /**
     * @param string $email Email
     * @param string $password Password
     * @return void // Defini la session (avec l'utilisation du token)
     * @throws AuthException
     */
    public static function authenticate(string $email, string $password) : bool
    {
        $user = User::getFromEmail($email);
        if(!$user)
        {
            throw new LoginInvalidEmailException("Email invalide");
        }
        if($user->checkPassword($password))
        {
            $_SESSION['user'] = $user;
            return true;
        }
        else
        {
            throw new LoginInvalidPasswordException("Mot de passe invalide");
        }
    }

    public static function register(string $email, string $password, string $role ='1') : bool{
        if (strlen($password) <= 10){
            throw new AuthException("Le mot de passe doit faire 10 caractères");
        }
        $user = User::getFromEmail($email);
        if ($user){
            throw new AuthException("L'utilisateur existe déjà");
        }
        $user = new User($email, password_hash($password, PASSWORD_DEFAULT), $role);
        $user->save();
        return true;
    }


}