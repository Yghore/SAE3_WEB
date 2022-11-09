<?php

namespace iutnc\netvod\auth;

use iutnc\netvod\exception\auth\AuthException;
use iutnc\netvod\exception\auth\LoginInvalidEmailException;
use iutnc\netvod\exception\auth\LoginInvalidPasswordException;
use iutnc\netvod\exception\auth\LoginInvalidUserException;
use iutnc\netvod\exception\auth\RegisterInvalidEmailException;
use iutnc\netvod\exception\auth\RegisterInvalidPasswordMatchException;
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
            if(!$user->isValid()){ throw new LoginInvalidUserException;}
            $_SESSION['user'] = $user;
            return true;
        }
        else
        {
            throw new LoginInvalidPasswordException("Mot de passe invalide");
        }
    }

    public static function register(string $email, string $password, string $role ='1') : bool{
        if (strlen($password) < 10){
            throw new RegisterInvalidPasswordMatchException("Le mot de passe doit faire 10 caractères");
        }
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $user = User::getFromEmail($email);
        if ($user){
            throw new RegisterInvalidEmailException("L'utilisateur existe déjà");
        }
        $user = new User();
        $user->pass = password_hash($password, PASSWORD_DEFAULT);
        $user->email = $email;

        $user->save();
        return true;
    }


}