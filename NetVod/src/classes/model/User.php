<?php

namespace iutnc\netvod\model;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\auth\AuthException;

class User
{
    private string $email;
    private string $password;
    private string $role;

    public function __construct(string $email, string $password, string $role = '1')
    {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
    public static function getFromSession()
    {
        if(isset($_SESSION['user'])){
            return $_SESSION['user'];
        }
        throw new AuthException("Vous n'êtes pas connecté");
    }

    public function save()
    {
        $db = ConnectionFactory::makeConnection();
        $query = $db->prepare("INSERT INTO user (email, pass) VALUES (:email, :password)");
        $query->execute([
            'email' => $this->email,
            'password' => $this->password
        ]);
    }

    public static function getFromEmail(string $email) : ?User
    {
        $db = ConnectionFactory::makeConnection();
        $query = $db->prepare("SELECT * FROM user WHERE email = :email");
        $query->execute([
            'email' => $email
        ]);
        $result = $query->fetch();
        if ($result) {
            return new User($result['email'], $result['pass']);
        }
        return null;
    }

    public function checkPassword($password){
        return password_verify($password, $this->password);
    }
}