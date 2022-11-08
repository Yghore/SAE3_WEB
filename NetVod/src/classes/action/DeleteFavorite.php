<?php

namespace iutnc\netvod\action;

use iutnc\netvod\model\User;

class DeleteFavorite extends Action
{

    protected function executeGET(): array
    {
        // TODO: Implement executeGET() method.
    }

    protected function executePOST(): array
    {
        $directory = $_POST['url'];
        $serie = $_POST['idserie'];
        User::getFromSession()->removeFavoriteSerie($serie);
        header('location: '. $directory);
        die();
    }
}