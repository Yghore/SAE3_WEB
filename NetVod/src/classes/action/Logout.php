<?php

namespace iutnc\netvod\action;

use iutnc\netvod\model\User;

class Logout extends Action
{

    protected function executeGET(): string
    {
        return <<<EOF
            
        EOF;
    }

    protected function executePOST(): string
    {
        return User::disconnect();
    }
}