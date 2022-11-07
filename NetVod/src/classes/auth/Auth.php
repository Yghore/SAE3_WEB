<?php

namespace iutnc\netvod\auth;

use iutnc\deefy\db\ConnectionFactory;

class Auth
{

    public static function login(string $email, string $password)
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT email from user");
    }


}