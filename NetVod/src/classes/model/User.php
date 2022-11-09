<?php

namespace iutnc\netvod\model;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\auth\AuthException;
use iutnc\netvod\exception\user\AttributException;
use iutnc\netvod\exception\video\InvalidPropertyNameException;
use PDO;
use iutnc\netvod\model\list\Serie;

class User
{
    protected int $id;
    protected string $email;
    protected string $pass;
    protected ?bool $valid;
    protected ?string $nom;
    protected ?string $prenom;
    protected ?string $date_birth;
    protected ?bool $parental_authorisation;

    public function __construct(){}

    public function __get(string $attribut) : mixed{
        if (property_exists($this, $attribut)){
            return $this->$attribut;
        } else {
            throw new InvalidPropertyNameException();
        }
    }

    public function __set(string $attribut, mixed $valeur){
        if (property_exists($this, $attribut)){
            $this->$attribut = $valeur;
        } else {
            throw new InvalidPropertyNameException("Attribut existe pas : $attribut");
        }
    }

    public function __isset($key) {
        return isset($this->$key);
    }



    public static function existSession(): bool
    {
        return isset($_SESSION['user']);
    }


    public static function existFromDatabase(string $email, string $pass): bool
    {
        $db = ConnectionFactory::makeConnection();
        $query = $db->prepare("SELECT email,pass from user where email = ? and pass = ? ");
        $query->execute([$email, $pass]);
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

    public function isValid() : bool
    {
        if(!isset($this->valid)) return false;
        return $this->valid;
    }

    public function save()
    {
        $db = ConnectionFactory::makeConnection();
        $query = $db->prepare("SELECT email FROM user WHERE email = ?");
        $query->execute([$this->email]);

        if($query->rowCount() > 0)
        {
            $query->closeCursor();
            $query = $db->prepare("UPDATE user SET nom = :nom, prenom = :prenom WHERE email = :email");
            $query->execute([':nom' => $this->nom, ':prenom' => $this->prenom, ':email' => $this->email]);
            return;
        }
        $query = $db->prepare("INSERT INTO user (email, pass) VALUES (:email, :password)");
        $query->execute([
            'email' => $this->email,
            'password' => $this->pass
        ]);

    }


    public static function getFromEmail(string $email): User | bool
    {
        $db = ConnectionFactory::makeConnection();
        $query = $db->prepare("SELECT * FROM user WHERE email = :email");
        $query->setFetchMode(PDO::FETCH_CLASS, User::class);

        $query->execute([
            'email' => $email
        ]);
        $user = $query->fetch();
        return $user;
    }

    public function checkPassword($password)
    {
        return password_verify($password, $this->pass);
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
        $state = $db->prepare("SELECT s.*, count(e.id) nbEpisodes FROM favorite2user INNER JOIN serie s on favorite2user.idserie = s.id INNER JOIN episode e on s.id = e.serie_id WHERE iduser = :user group by e.serie_id having count(e.id) > 0");
        $state->setFetchMode(PDO::FETCH_CLASS, Serie::class);
        $state->execute([':user' => $this->id]);
        return $state->fetchAll();
    }

    public function getCurrentSeries(): array
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT s.*, count(e.id) as nbEpisodes FROM current2user INNER JOIN serie s on current2user.idserie = s.id INNER JOIN episode e on s.id = e.serie_id WHERE iduser = ? group by e.serie_id HAVING count(e.id) > 0");
        $state->setFetchMode(PDO::FETCH_CLASS, Serie::class);
        $state->execute([$this->id]);
        return $state->fetchAll();
    }

    public function isFavoriteSerie(int $serieid): bool
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT idserie FROM favorite2user WHERE iduser = :user AND idserie = :serie");
        $state->execute([':user' => $this->id, ':serie' => $serieid]);
        return $state->rowCount() >= 1;
    }

    public static function disconnect(): string
    {
        if (isset($_SESSION['user'])) {
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
            $res .= "</div>";

        }
        return $res;
    }


}