<?php

namespace iutnc\netvod\model;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\auth\AuthException;
use iutnc\netvod\exception\user\AttributException;
use iutnc\netvod\model\list\Serie;
use PDO;

class User
{
    private string $email;
    private string $password;
    private string $role;
    private int $id;
    private string $nom;
    private string $prenom;
    private int $age;

    public function __get(string $name): mixed
    {
        if (property_exists($this, $name) && $name != "password") {
            return $this->$name;
        }
        throw new AttributException("$name n'existe pas");

    }

    public function __construct(string $email, string $password, int $id, string $role = '1')
    {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->id = $id;
    }

    public static function existSession(): bool
    {
        return isset($_SESSION['user']);
    }


     public static function existFromDatabase(string $email, string $pass) : bool{
        $db = ConnectionFactory::makeConnection();
        $query = $db->prepare("SELECT email,pass from USER where email = ? and pass = ? ");
        $query->execute([$email,$pass]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result != null;

    }

    public static function getFromSession(): User
    {
        if (isset($_SESSION['user'])) {
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

    public static function getFromEmail(string $email): ?User
    {
        $db = ConnectionFactory::makeConnection();
        $query = $db->prepare("SELECT * FROM user WHERE email = :email");
        $query->execute([
            'email' => $email
        ]);
        $result = $query->fetch();
        if ($result) {
            return new User($result['email'], $result['pass'], $result['id']);
        }
        return null;
    }

    public function checkPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function addFavoriteSerie(int $idSerie): bool
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("INSERT INTO favorite2user VALUES(:user, :serie)");
        return $state->execute([':user' => $this->id, ':serie' => $idSerie]);
    }

    public function getFavoritesSeries(): array
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT * FROM favorite2user INNER JOIN serie s on favorite2user.idserie = s.id WHERE iduser = :user");
        $state->setFetchMode(PDO::FETCH_CLASS, Serie::class);
        $state->execute([':user' => $this->id]);
        return $state->fetchAll();
    }

    public function isFavoriteSerie(int $serieid): bool
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT idserie FROM favorite2user WHERE iduser = :user AND idserie = :serie");
        $state->execute([':user' => $this->id, ':serie' => $serieid]);
        return $state->rowCount() >= 1;
    }

    public function removeFavoriteSerie(int $serieid): bool
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("DELETE FROM favorite2user WHERE iduser = :user AND idserie = :serie");
        return $state->execute([':user' => $this->id, ':serie' => $serieid]);
    }

    public function putCommentNoteSerie(int $id, string $comment, int $note)
    {
        $iduser = User::getFromSession()->getId();
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("INSERT INTO comment2user (idserie,iduser,commentaire,note ) VALUES (?, ?, ?, ?)");
        $state->execute([$id, $iduser, $comment, $note]);
    }

    public static function disconnect(): string
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            $res = <<<EOF
            <div class="alert alert-success" role="alert">
                Vous êtes déconnecté
            EOF;

        } else {
            $res = <<<EOF
            <div class="alert alert-danger" role="alert">
                Vous n'êtes pas connecté
            EOF;

        }
        return $res;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }


}