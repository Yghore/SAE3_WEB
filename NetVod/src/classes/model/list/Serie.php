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

    protected array $episodes;

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
            throw new InvalidPropertyNameException("Attribut existe pas : $attribut");
        }
    }

    public function ajouterEpisode(Episode $episode){
        $this->episodes[] = $episode;
    }

    public function ajouterListeEpisode(array $listeEpisodes){
        foreach ($listeEpisodes as $episode){
            $this->ajouterEpisode($episode);
        }
    }

    /*public function getDescriptionSerie(int $id) :array {
        $pdo = ConnectionFactory::makeConnection();
        $episodes = [];
        $query2 = <<<end
            SELECT
              *
            FROM
                episode
            WHERE serie_id = ?
            end;
        $resultatSet2 = $pdo->prepare($query2);
        $resultatSet2->execute([$id]);
        while ($row2 = $resultatSet2->fetch()) {
            $idepisode = $row2['id'];
            $titre2 = $row2['titre'];
            $resume2 = $row2['resume'];
            $numero2 = $row2['numero'];
            $duree = $row2['duree'];
            $file = $row2['file'];
            $img = '';
            $episodes[] = new Episode($titre2, $file);
            $episodes->numero = $numero2;
            $episodes->resume = $resume2;
            $episodes->duree = $duree;
            $episodes->serie_id = $id;
            //$html .= "<li><a href=\"index.php?action=print-catalogue&id=$id&idepisode=$idepisode\"> $numero2 $titre2 $duree $img </a></li>";
        }
        $this->episodes = $episodes;
        return $episodes;
    }*/

    public static function nbSerie(): int {
        $pdo = ConnectionFactory::makeConnection();
        $query = <<<end
            SELECT
                COUNT(id) as 'nb'
            FROM
                serie
            end;
        $resultatSet = $pdo->prepare($query);
        $resultatSet->execute();
        $nbSerie = $resultatSet->fetch()['nb'];
        return $nbSerie;
    }



    public static function getSerie(int $id) : Serie
    {
        $pdo = ConnectionFactory::makeConnection();
        $query = <<<end
            SELECT
                serie.titre, serie.id, serie.descriptif, serie.img, serie.annee, serie.date_ajout, COUNT(episode.serie_id) as 'nbepisodes'
            FROM
                serie
            INNER JOIN episode ON episode.serie_id = serie.id
            WHERE
                serie.id = ?
            end;
        $resultatSet = $pdo->prepare($query);
        $resultatSet->execute([$id]);
        $serieid = '';
        while ($row = $resultatSet->fetch()) {
            $serie = new Serie();
            $serie->titre = $row['titre'];
            $serie->id = $row['id'];
            $serie->descriptif = $row['descriptif'];
            $serie->img = $row['img'];
            $serie->annee= $row['annee'];
            $serie->date_ajout = $row['date_ajout'];
            $serie->nbEpisodes = $row['nbepisodes'];
        }
        return $serie;
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

    public static function MoyenneNoteSerie($idSerie) : mixed{
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
}