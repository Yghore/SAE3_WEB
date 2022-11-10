<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\model\User;
use iutnc\netvod\render\auth\TokenRenderer;
use iutnc\netvod\model\Token as TokenAction;

class Token extends Action
{

    protected function executeGET(): string
    {
        if(isset($_GET['token']))
        {
            $token = $_GET['token'];
            $db = ConnectionFactory::makeConnection();
            $state = $db->prepare("SELECT iduser, UNIX_TIMESTAMP(date_expiration) as expiration FROM token_reset WHERE token = ?");
            $state->execute([$token]);
            if($state->rowCount() > 0)
            {
                $res = $state->fetch();
                if($res['expiration'] > time())
                {
                    $user = User::getFromUserId($res['iduser']);
                    $user->valid = true;
                    $user->save();
                    TokenAction::deleteTokenFormDB($token);
                    return (new TokenRenderer("Compte validé !", 'success-text'))->render();

                }
                else
                {
                    return (new TokenRenderer("Token expiré"))->render();
                }

            }
            else
            {
                return (new TokenRenderer("Token invalide"))->render();
            }
        }
        return (new TokenRenderer("Compte validé !", 'success-text'))->render();

    }

    protected function executePOST(): string
    {
        // TODO: Implement executePOST() method.
    }
}