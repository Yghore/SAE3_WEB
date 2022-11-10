<?php

namespace iutnc\netvod\model;

use DateTime;
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
    protected ?bool $parental = false;
    protected ?array $genres;

    public function isStricted(): bool {
        //verifie si l'utilisateur est mineur
        if ($this->date_birth != null) {
            $date = new DateTime($this->date_birth);
            $now = new DateTime();
            $interval = $now->diff($date);
            return $interval->y < 18;
        } else {
            return false;
        }
    }

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

    public static function emailExistInDatabase(string $email) : bool{
        $db = ConnectionFactory::makeConnection();
        $query = $db->prepare("SELECT email from user where email = ? ");
        $query->execute([$email]);
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
            $query = $db->prepare("UPDATE user SET nom = :nom, prenom = :prenom, valid = :valid, date_birth = :date_birth WHERE email = :email");
            $query->execute([':nom' => $this->nom, ':prenom' => $this->prenom, ':email' => $this->email, ':valid' => $this->valid, ':date_birth' => $this->date_birth]);
            return;
        }
        $query = $db->prepare("INSERT INTO user (email, pass, valid) VALUES (:email, :password, :valid)");
        $query->execute([
            'email' => $this->email,
            'password' => $this->pass,
            'valid' => 0
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


    public static function getFromUserId(int $userid): User | bool
    {
        $db = ConnectionFactory::makeConnection();
        $query = $db->prepare("SELECT * FROM user WHERE id = :user");
        $query->setFetchMode(PDO::FETCH_CLASS, User::class);

        $query->execute([
            'user' => $userid
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

    public function deleteFavoriteSerie(int $idSerie): bool
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("DELETE FROM favorite2user WHERE iduser = :user AND idserie = :serie");
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
        // on selectionne les series en cours qui ne sont pas achevées
        $state = $db->prepare("SELECT s.*, count(e.id) as nbEpisodes FROM current2user INNER JOIN serie s on current2user.idserie = s.id INNER JOIN episode e on s.id = e.serie_id WHERE iduser = ? AND NOT current2user.currentEpisode = (SELECT MAX(e.id) FROM episode e WHERE e.serie_id=s.id) GROUP BY e.serie_id HAVING count(e.id) > 0");
        // On les recupere en tant qu'objets Serie
        $state->setFetchMode(PDO::FETCH_CLASS, Serie::class);
        $state->execute([$this->id]);
        return $state->fetchAll();
    }

    public function getCompletedSeries(): array{
        $pdo = ConnectionFactory::makeConnection();
        // La requête récupère les séries dont l'utilisateur a vu tous les épisodes (current2user.currentEpisode correspond au dernier épisode de la série)
        $query = <<<end
            SELECT s.*, COUNT(e.serie_id) as nbEpisodes
            FROM serie s INNER JOIN episode e on s.id = e.serie_id
            INNER JOIN current2user c ON c.idserie = s.id
            WHERE c.iduser = ?
            AND c.currentEpisode = (SELECT MAX(e.id) FROM episode e WHERE e.serie_id=s.id)
            GROUP BY e.serie_id HAVING COUNT(e.serie_id) > 0;
            end;
        $resultSet = $pdo->prepare($query);
        $resultSet->setFetchMode(PDO::FETCH_CLASS, Serie::class);
        $resultSet->execute([$this->id]);
        // on renvoie le tableau de séries que l'utilisateur a terminé
        return $resultSet->fetchAll();
    }

    public function isFavoriteSerie(int $serieid): bool
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT idserie FROM favorite2user WHERE iduser = :user AND idserie = :serie");
        $state->execute([':user' => $this->id, ':serie' => $serieid]);
        return $state->rowCount() >= 1;
    }

    public static function getIdUserFromEmail(string $email): int
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT id FROM user WHERE email = :email");
        $state->execute([':email' => $email]);
        return $state->fetch(PDO::FETCH_ASSOC)['id'];
    }

    public function putCommentNoteSerie($idserie, $note, $commentaire)
    {

        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("INSERT INTO comment2user (idserie, iduser, note, commentaire) VALUES (?,?,?,?)");
        $state->execute([$idserie,$this->id,$note,$commentaire]);
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