<?php

namespace iutnc\netvod\model\video;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\video\InvalidPropertyValueException as InvalidPropertyValueException;
use iutnc\netvod\exception\video\InvalidPropertyNameException;

class Episode
{

    protected int $id;

    protected int $numero;

    protected string $titre;

    protected string $resume;

    protected int $duree;

    protected string $filename;

    protected int $serie_id;

    public function __construct(string $titre, $filename){
        $this->titre = $titre;
        $this->filename = $filename;
    }

    public function __toString(): string{
        return json_encode($this);
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
            if ($attribut !== 'filename'){
                $this->$attribut = $valeur;
            } else {
                throw new InvalidPropertyValueException();
            }
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
        $resultatSet2->execute([$idEpisode]);
        while ($row2 = $resultatSet2->fetch()) {
            $episode = new Episode($row2['titre'], $row2['file']);
            $episode->id = $row2['id'];
            $episode->duree = $row2['duree'];
            $episode->serie_id = $row2['serie_id'];
            $episode->numero = $row2['numero'];
            $episode->resume = $row2['resume'];
        }
        return $episode;
    }
}