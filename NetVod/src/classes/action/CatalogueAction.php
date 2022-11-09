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
        if (!isset($_GET['id'])) {
            $html = $this->allSeries();
        } else {
            $idserie = $_GET['id'];
            if (!isset($_GET['idepisode'])) {
                $html = $this->isSerie($idserie);

            } else {
                $idepisode = $_GET['idepisode'];
                $html = $this->isEpisode($idepisode, $idserie);
            }
        }
        return $html;
    }

    private function allSeries() : string
    {
        if (User::existSession()){
            return (new SeriesRenderer(Serie::getSeries()))->render(2);
        } else {
            return "Vous n'etes pas connecté";
        }
    }

    private function isSerie(int $idserie) : string
    {
        $html = "";
        $serie = Serie::getSerie($idserie);
        $render = new SerieRenderer($serie, $_SERVER['REQUEST_URI']);
        $html .= $render->render(1);
        $episodes = Serie::getAllEpisodes($idserie);
        $html .= "<div class='list-card'>";
        foreach ($episodes as $episode){
            $render = new EpisodeRenderer($episode, $serie);
            $html .= $render->render(1);
        }
        $html .= "</div>";
        // On vérifie que l'utilisateur est connecté

        return $html;
    }

    private function isEpisode(int $idepisode, int $idserie)
    {
        $idepisode = $_GET['idepisode'];
        $episode = Episode::getEpisode($idepisode);
        $render = new EpisodeRenderer($episode);
        if(User::existSession()){
            // On insère dans la table current2user la série en cours si elle n'y est pas déjà
            $pdo = ConnectionFactory::makeConnection();
            $rs3 = $pdo->prepare("INSERT IGNORE INTO current2user VALUES (?,?)");
            $iduser = User::getFromSession()->id;
            $rs3->execute([$iduser,$idserie]);

        }
        return $render->render(2);

    }

    protected function executePOST(): string
    {
        $html = '';
        return $html;
    }
}