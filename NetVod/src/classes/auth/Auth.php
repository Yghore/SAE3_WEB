<?php

namespace iutnc\netvod\auth;

use iutnc\deefy\db\ConnectionFactory;
use MongoDB\Driver\Exception\ConnectionException;

class Auth
{

    public static function login(string $email, string $password)
    {
        $db = ConnectionFactory::makeConnection();
    }


}