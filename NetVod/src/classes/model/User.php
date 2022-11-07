<?php

namespace iutnc\netvod\model;

use iutnc\deefy\db\ConnectionFactory;

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

    public function save()
    {
        $db = ConnectionFactory::makeConnection();
        $query = $db->prepare("INSERT INTO user (email, password, role) VALUES (:email, :password, :role)");
        $query->execute([
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role
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
            $user = new User($result['email'], $result['password'], $result['role']);
            return $user;
        }
        return null;
    }
}