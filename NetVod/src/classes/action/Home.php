<?php

namespace iutnc\netvod\action;

use iutnc\netvod\exception\auth\AuthException;
use iutnc\netvod\model\User;
use iutnc\netvod\render\SeriesRenderer;

class Home extends Action
{

    protected function executeGET(): string
    {
        try
        {
            $user = User::getFromSession();
            $favoris = $user->getFavoritesSeries();
            if (count($favoris)>0){
                $html = "<h2>Vos favoris :</h2>\n";
                $html .= (new SeriesRenderer($favoris))->render(2);
            } else {
                $html = "<div class=\"message-center\"><h2>Vous n'avez pas encore de favoris</h2>\n";
                $html .= "<p>Vous pouvez ajouter des séries à vos favoris en cliquant sur le bouton \"ajouter aux favoris\" dans la page d'une série</p></div>\n";
            }
            
            $current = $user->getCurrentSeries();
            if (count($current)>0){
                $html .= "<h2>Vos séries en cours :</h2>\n";
                $html .= (new SeriesRenderer($current))->render(2);
            } else {
                $html .= "<div class=\"message-center\"><h2>Vous n'avez pas de série en cours</h2>\n";
                $html .= "<p>Commencez à visionner une série pour qu'elle s'affiche ici</p></div>\n";
            }
            $watched = $user->getCompletedSeries();
            if (count($watched)>0){
                $html .= "<h2>Vos séries visionnées :</h2>\n";
                $html .= (new SeriesRenderer($watched))->render(2);
            }

            return $html;
        }
        catch (AuthException $e)
        {

            return $e->getMessage();
        }
    }

    protected function executePOST(): string
    {
        // TODO: Implement executePOST() method.
        return "";
    }
}