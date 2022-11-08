<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\exception\AuthException;
use iutnc\netvod\model\User;

class Signin extends Action
{

    protected function executeGET(): array
    {
        try
        {
            $user = User::getFromSession();
            return ['view' => 'signin/connected', '$email' => $user->email];


        }
        catch(\iutnc\netvod\exception\auth\AuthException)
        {
            return ['view' => 'signin/signin'];
        }
    }

    protected function executePOST(): array
    {
        $content = '';
        try {
            if (Auth::authenticate($_POST['email'], $_POST['password'])) {
                $user = User::getFromEmail($_POST['email']);
                if (!$user) {
                    $user = $_SESSION['user'];
                };
            }
        } catch (AuthException $e) {
            print($e->getMessage());
        }

        return ['view' => 'signin/connected', '$email' => $user->email];
    }
}