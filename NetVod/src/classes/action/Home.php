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
            $html = "<h2>Vos favoris :</h2>\n";
            $html .= (new SeriesRenderer($user->getFavoritesSeries()))->render(2);
            $html .= "<h2>Vos séries en cours :</h2>\n";
            $html .= (new SeriesRenderer($user->getCurrentSeries()))->render(2);
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