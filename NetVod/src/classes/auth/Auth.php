<?php

namespace iutnc\netvod\auth;

use iutnc\deefy\db\ConnectionFactory;

class Auth
{

    public static function login(string $email, string $password)
    {
        $db = ConnectionFactory::makeConnection();
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




