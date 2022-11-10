<?php

namespace iutnc\netvod\model;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\video\InvalidPropertyNameException;
use PDO;

class Comment2user
{
    protected int $idserie;
    protected int $iduser;
    protected int $note;
    protected string $commentaire;


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

    public static function getCommentaireFromSerie(int $serieid) : array
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT * FROM comment2user WHERE idserie = ?");
        $state->setFetchMode(PDO::FETCH_CLASS, Comment2user::class);
        $state->execute([$serieid]);
        return $state->fetchAll();
    }

    public function getUsername()
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT email FROM user WHERE id = ?");
        $state->execute([$this->iduser]);
        return $state->fetch()['email'];

    }


}