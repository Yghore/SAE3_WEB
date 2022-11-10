<?php

namespace iutnc\netvod\model\list;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\video\InvalidPropertyNameException;
use iutnc\netvod\model\video\Episode;
use PDO;

class Serie
{

    protected int $id;

    protected string $titre;

    protected string $descriptif;

    protected string $img;

    protected int $annee;

    protected string $date_ajout;

    protected int $nbEpisodes;

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
        } else {
            echo $attribut;
            throw new InvalidPropertyNameException("Attribut existe pas : $attribut");
        }
    }

    public static function getSerie(int $id) : Serie
    {
        $pdo = ConnectionFactory::makeConnection();
        $query = <<<end
            SELECT
                serie.titre, serie.id, serie.descriptif, serie.img, serie.annee, serie.date_ajout, COUNT(episode.serie_id) as 'nbEpisodes'
            FROM
                serie
            INNER JOIN episode ON episode.serie_id = serie.id
            WHERE
                serie.id = ?
            end;
        $resultatSet = $pdo->prepare($query);
        $resultatSet->setFetchMode(PDO::FETCH_CLASS, Serie::class);
        $resultatSet->execute([$id]);
        return $resultatSet->fetch();
    }

    public static function getSeries() : array
    {
        $bd = ConnectionFactory::makeConnection();
        $state = $bd->prepare("SELECT serie.*, COUNT(e.serie_id) as nbEpisodes FROM serie INNER JOIN episode e on serie.id = e.serie_id group by e.serie_id having count(e.serie_id) > 0");
        $state->setFetchMode(PDO::FETCH_CLASS, Serie::class);
        $state->execute();
        return $state->fetchAll();
    }


    public static function getAllEpisodes($idSerie) : array {
        $episodes = [];
        $pdo = ConnectionFactory::makeConnection();
        $query2 = <<<end
            SELECT
              id
            FROM
                episode
            WHERE serie_id = ?
            end;
        $resultatSet2 = $pdo->prepare($query2);
        $resultatSet2->execute([$idSerie]);
        while ($row2 = $resultatSet2->fetch()) {
            $id = $row2['id'];
            $episodes[] = Episode::getEpisode($id);
        }
        return $episodes;
    }

    public static function moyenneNoteSerie($idSerie) : mixed{
        $noter = false;
        $pdo = ConnectionFactory::makeConnection();
        $query3 = <<<end
                 SELECT
                    avg(note) as note
                 FROM
                    comment2user
                 WHERE   
                    idserie = $idSerie
                   
                end;
        $resultatSet3 = $pdo->prepare($query3);
        $resultatSet3->execute();
        $row3 = $resultatSet3->fetch();
        if ($row3['note'] !== null) {
            $noter = $row3['note'];
        }
        return $noter;
    }

    public static function getSeriesByKeywords(array $keywords): array{
        $pdo = ConnectionFactory::makeConnection();
        $query = <<<end
            SELECT DISTINCT serie.*, COUNT(e.serie_id) as nbEpisodes
            FROM serie INNER JOIN episode e on serie.id = e.serie_id
            WHERE serie.titre LIKE ?
            OR serie.descriptif LIKE ?
            GROUP BY e.serie_id HAVING COUNT(e.serie_id) > 0;
            end;
        $resultSet = $pdo->prepare($query);
        $resultSet->setFetchMode(PDO::FETCH_CLASS, Serie::class);
        $resultats = [];
        foreach ($keywords as $keyword){
            $resultSet->execute(["%$keyword%", "%$keyword%"]);
            $resultats = array_merge($resultats, $resultSet->fetchAll());
        }
        return array_unique($resultats, SORT_REGULAR);
    }
}