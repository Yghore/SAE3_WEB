<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\model\User;

class CatalogueAction extends Action
{

    protected function executeGET(): string
    {
        $html = '';
        $pdo = ConnectionFactory::makeConnection();
        if (! isset($_GET['id'])){
            $query = <<<end
            SELECT
                id,
                titre,
                img
            FROM
                serie
        end;
            $resultatSet = $pdo->prepare($query);
            $resultatSet->execute();
            $html .= '<form action="index.php?action=add-user" method="post">';
            $html .= '<nav><ul>';
            while ($row = $resultatSet->fetch()){
                $idserie = $row['id'];
                $titre = $row['titre'];
                $image = $row['img'];
                $html .= "<li><a href=\"index.php?action=print-catalogue&id=$idserie\">$titre $image</a></li>";
            }
            $html .= '</nav></ul>';
        } else {
            $idserie = $_GET['id'];
            if (! isset($_GET['idepisode'])){
                $query = <<<end
            SELECT
                *,
                COUNT(episode.serie_id) as 'nbepisodes'
            FROM
                serie
            INNER JOIN episode ON episode.serie_id = serie.id
            WHERE
                serie.id = ?
            end;
            $resultatSet= $pdo->prepare($query);
            $resultatSet->execute([$idserie]);
            $serieid = '';
            while ($row = $resultatSet->fetch()){
                $serieid = $row['id'];
                $html .= 'titre : ' .  $row['titre'] . "<br/>";
                $html .= 'descriptif : ' . $row['descriptif'] . "<br/>";
                $html .= 'img : ' . $row['img'] . "<br/>";
                $html .= 'annee : ' . $row['annee'] . "<br/>";
                $html .= 'date d ajout : ' . $row['date_ajout'] . "<br/>";
                $html .= 'nombre d épisodes : ' . $row['nbepisodes'] . "<br/>";
            }
            if(User::getFromSession()->isFavoriteSerie($idserie))
            {
                $html .= "Cette série est dans les favoris";
            }
            else
            {
                $html .= <<<EOF
                <form method="POST" action="?action=add-favorite">
                    <input type="hidden" name="url" value="{$_SERVER['REQUEST_URI']}">
                    <input type="hidden" name="idserie" value="$idserie">
                    <input type="submit" value="Ajouter au favoris">
                </form>
                EOF;
            }

                $query2 = <<<end
            SELECT
              *
            FROM
                episode
            WHERE serie_id = ?
            end;
                $resultatSet2 = $pdo->prepare($query2);
                $resultatSet2->execute([$idserie]);
                while ($row2 = $resultatSet2->fetch()){
                    $idepisode = $row2['id'];
                    $titre2 = $row2['titre'];
                    $numero2 = $row2['numero'];
                    $duree = $row2['duree'];
                    $img = '';
                    $html .= "<li><a href=\"index.php?action=print-catalogue&id=$idserie&idepisode=$idepisode\"> $numero2 $titre2 $duree $img </a></li>";
                }
            } else {

            }

        }
        return $html;
    }

    protected function executePOST(): string
    {
        $html = 'yo';
        return $html;
    }
}
