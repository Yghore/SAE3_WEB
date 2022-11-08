<?php

namespace iutnc\netvod\action;

use iutnc\netvod\model\User;

class AddFavorite extends Action
{

    protected function executeGET(): string
    {
        // TODO: Implement executeGET() method.
    }

    protected function executePOST(): string
    {
        var_dump($_POST);
        $directory = $_POST['url'];
        $serie = $_POST['idserie'];
        User::getFromSession()->addFavoriteSerie($serie);
        header('location: '. $directory);
        die();
    }
}