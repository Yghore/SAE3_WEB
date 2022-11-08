<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\model\list\Serie;
use iutnc\netvod\model\User;
use iutnc\netvod\render\SerieRenderer;

class CatalogueAction extends Action
{

    protected function executeGET(): string
    {
        $html = '';
        $pdo = ConnectionFactory::makeConnection();
        if (!isset($_GET['id'])) {

            $nbSerie = Serie::nbSerie();
            for ($i = 1; $i <= $nbSerie; $i++){
                 $serie = Serie::getSerie($i);
                 $render = new SerieRenderer($serie);
                 $html .= $render->render(2);
            }
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
            while ($row = $resultatSet->fetch()) {
                $idserie = $row['id'];
                $titre = $row['titre'];
                $image = $row['img'];
                $query3 = <<<end
                SELECT
                    avg(note) as note
                FROM
                comment2user
                WHERE   
                    idserie = $idserie
                   
                end;
                $resultatSet3 = $pdo->prepare($query3);
                $resultatSet3->execute();
                $row3 = $resultatSet3->fetch();
                if ($row3['note'] == null) {
                    $html .= <<<end
                    <li><a href="index.php?action=print-catalogue&id=$idserie">$titre $image</a> <a href="?action=add-comment-note&id=$idserie"><button>Ajouter AVIS</button></a>
                <p>Il n'y a pas encore de note pour cette série</p>
                </li>
                end;
                } else {
                    $note = $row3['note'];
                    $html .= <<<EOF
                    <li><a href="index.php?action=print-catalogue&id=$idserie">$titre $image</a> 
                <p>La note moyenne de cette série est de $note</p>
                </li>
                EOF;
                }
            }
        } else {
            $idserie = $_GET['id'];
            if (!isset($_GET['idepisode'])) {
                $serie = Serie::getSerie($idserie);
                $render = new SerieRenderer($serie);
                $html .= $render->render(1);
                $html .= $render->render(2);

                if (User::getFromSession()->isFavoriteSerie($idserie)) {
                    $html .= "Cette série est dans les favoris";
                } else {
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
                while ($row2 = $resultatSet2->fetch()) {
                    $idepisode = $row2['id'];
                    $titre2 = $row2['titre'];
                    $numero2 = $row2['numero'];
                    $duree = $row2['duree'];
                    $img = '';
                    $html .= "<li><a href=\"index.php?action=print-catalogue&id=$idserie&idepisode=$idepisode\"> $numero2 $titre2 $duree $img </a></li>";

                }
            } else {
                $idepisode = $_GET['idepisode'];
                $query = <<<end
            SELECT  
                *
            FROM
                episode
            WHERE
                id = ?
            end;
                $resultatSet = $pdo->prepare($query);
                $resultatSet->execute([$idepisode]);
                while ($row = $resultatSet->fetch()) {
                    $titre = $row['titre'];
                    $numero = $row['numero'];
                    $resume = $row['resume'];
                    $duree = $row['duree'];
                    $img = '';
                    $html .= "<li><a href=\"index.php?action=print-catalogue&id=$idserie&idepisode=$idepisode\"> $numero $titre $duree $img </a></li>";
                    $html .= "</br>$titre";
                    $html .= "</br>$resume";
                    $html .= "</br>$duree";
                }
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
