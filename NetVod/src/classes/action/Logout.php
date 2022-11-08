<?php

namespace iutnc\netvod\action;

use iutnc\netvod\model\User;

class Logout extends Action
{

    protected function executeGET(): string
    {
        return <<<EOF
            <h1>Déconnexion</h1>
            <form action="index.php?action=logout" method="post">
              <input type="submit" value="Déconnexion">
            </form>
        EOF;
    }

    protected function executePOST(): string
    {
        return User::disconnect();
    }
}