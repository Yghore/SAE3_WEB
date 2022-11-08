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
            return (new SeriesRenderer($user->getFavoritesSeries()))->render();
        }
        catch (AuthException)
        {
            return "Vous ne semblez pas connecté, merci de vous connecter pour voir vos favoris";
        }
    }

    protected function executePOST(): string
    {
        // TODO: Implement executePOST() method.
        return "";
    }
}