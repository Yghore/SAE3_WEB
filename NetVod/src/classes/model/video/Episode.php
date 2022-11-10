<?php

namespace iutnc\netvod\model\video;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\video\InvalidPropertyValueException as InvalidPropertyValueException;
use iutnc\netvod\exception\video\InvalidPropertyNameException;
use PDO;

class Episode
{

    protected int $id;

    protected int $numero;

    protected string $titre;

    protected string $resume;

    protected int $duree;

    protected string $file;

    protected int $serie_id;

    protected string $img;

    public function __toString(): string{
        return json_encode($this);
    }

    public function __get(string $attribut) : mixed{
        if (property_exists($this, $attribut)){
            return $this->$attribut;
        } else {
            echo $attribut;
            throw new InvalidPropertyNameException();
        }
    }

    public function __set(string $attribut, mixed $valeur){
        if (property_exists($this, $attribut)){
            $this->$attribut = $valeur;
            throw new InvalidPropertyValueException();
        } else {
            throw new InvalidPropertyNameException();
        }
    }

    public function getThumbnails() : string
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT s.img FROM serie s INNER JOIN episode e ON  s.id = e.serie_id WHERE serie_id = ? LIMIT 1");
        $state->execute([$this->serie_id]);
        return $state->fetch()['img'];

    }

    public static function getEpisode($idEpisode) : Episode
    {
        $pdo = ConnectionFactory::makeConnection();
        $query2 = <<<end
            SELECT
              *
            FROM
                episode
            WHERE id = ?
            end;
        $resultatSet2 = $pdo->prepare($query2);
        $resultatSet2->setFetchMode(PDO::FETCH_CLASS, Episode::class);
        $resultatSet2->execute([$idEpisode]);
        return $resultatSet2->fetch();
    }
}