<?php

namespace iutnc\netvod\dispatcher;

class Dispatcher
{
    public function run() : string
    {
        $_GET['action'] = $_GET['action'] ?? "";
        $content='
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>NetVod - '.$_GET['action'].'</title>
        </head>
        <body>
        switch ($action)
        ';

        switch ($_GET['action']){
            case 'add-user':
                //TODO
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