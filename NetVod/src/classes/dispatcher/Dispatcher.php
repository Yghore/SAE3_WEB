<?php

namespace iutnc\netvod\dispatcher;

use iutnc\netvod\action\AddUser;

class Dispatcher
{
    public function run()
    {
        $html = '';
        switch ($action){
            case 'add-user':
                $action = new AddUser();
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
                $action = null;
                $html = "Bienvenue";
                break;
        }
        if ($action !== null){
            $html = $action->execute();
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html){
        echo <<<END
        <!DOCTYPE html>
        <html lang = "fr">
            <head>
                <title>NetVod - Video Streaming</title>
                <meta charset= "utf8" />
            </head>
            
            <body>
                <h1>NetVod - Video Streaming</h1>
                <nav><ul>
                    <li><a href="?action=add-user">Ajouter un utilisateur</a></li>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="?action=add-serial">ajouter une serie</a></li>
                    <li><a href="?action=print-catalogue">afficher la categorie</a></li>
                    <li><a href="?action=add-episode">ajouter un episode</a></li>
                </nav></ul>
                <h2>$html</h2>
            </body>
        </html>
    END;
    }
}