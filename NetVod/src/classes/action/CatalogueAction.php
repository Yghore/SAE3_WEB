<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\model\list\Serie;
use iutnc\netvod\model\User;
use iutnc\netvod\model\video\Episode;
use iutnc\netvod\render\EpisodeRenderer;
use iutnc\netvod\render\SerieRenderer;
use iutnc\netvod\render\SeriesRenderer;

class CatalogueAction extends Action
{

    protected function executeGET(): string
    {
        $html = '';
        $pdo = ConnectionFactory::makeConnection();
        if (!isset($_GET['id'])) {
            if (User::existSession()){
                $html =  (new SeriesRenderer(Serie::getSeries()))->render(2);
            } else {
                $html = "Vous n'etes pas connecté";
            }
        } else {
            $idserie = $_GET['id'];
            if (!isset($_GET['idepisode'])) {
                $serie = Serie::getSerie($idserie);
                $render = new SerieRenderer($serie);
                $html .= $render->render(1);
                try {
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
                    $episodes = Serie::getAllEpisodes($idserie);
                    $html .= "<div class='list-card'>";
                    foreach ($episodes as $episode){
                        $render = new EpisodeRenderer($episode);
                        $html .= $render->render(1);
                    }
                    $html .= "</div>";
                    // On vérifie que l'utilisateur est connecté
                    if(User::existSession()){
                        // On insère dans la table current2user la série en cours si elle n'y est pas déjà
                        $query3 = <<<end
                    INSERT IGNORE INTO current2user
                    VALUES (?,?)
                    end;
                        $rs3 = $pdo->prepare($query3);
                        try {
                            $iduser = User::getFromSession()->id;
                            $rs3->execute([$iduser,$idserie]);
                        } catch (\Exception $e){
                            echo $e->getMessage();
                        }

                    }
                } catch (\Exception $e){
                    $html = $e->getMessage();
                }

            } else {
                $idepisode = $_GET['idepisode'];
                $episode = Episode::getEpisode($idepisode);
                $render = new EpisodeRenderer($episode);
                $html .= $render->render(2);
            }
        }
        return $html;
    }

    protected function executePOST(): string
    {
        $html = '';
        return $html;
    }
}