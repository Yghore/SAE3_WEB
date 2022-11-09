<?php

namespace iutnc\netvod\action;

use iutnc\netvod\model\User;

class DeleteFavorite extends Action
{

    protected function executeGET(): string
    {
        // TODO: Implement executeGET() method.
        header('location: ?action=home');
        die();
    }

    protected function executePOST(): string
    {
        $directory = $_POST['url'];
        $serie = $_POST['idserie'];
        User::getFromSession()->deleteFavoriteSerie($serie);
        header('location: '. $directory);
        die();
    }
}