<?php

namespace iutnc\netvod\dispatcher;

use iutnc\netvod\action\AddUser;

class Dispatcher
{
    public function run() : string
    {
        $action = (!empty($_GET['action'])) ? $_GET['action'] : "default";
        $content= <<<EOF
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>NetVod - Video Streaming</title>
        </head>
        <body>
        EOF;

        switch ($action){
            case 'add-user':
                $action = new AddUser();
                $content = $action->execute();
                break;
            case 'signin':
                //TODO
                break;
            case 'add-serial':
                //TODO
                break;
            case 'print-catalogue':
                //TODO
                break;
            case 'add-episode':
                //TODO
                break;
            default:
                //TODO
                break;
        }

        $content .= '</body></html>';

        return $content;
    }
}