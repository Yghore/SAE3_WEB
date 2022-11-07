<?php

namespace iutnc\netvod\dispatcher;

use iutnc\netvod\action\AddUser;
use iutnc\netvod\action\Signin;

class Dispatcher
{
    public function run()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        } else {
            $action = 'home';
        }
        $html = '';
        switch ($action){
            case 'add-user':
                $user = new AddUser();
                $html .= $user->execute();
                break;

            case 'signin':
                $signin = new Signin();
                $html .= $signin->execute();
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
                    <li><a href="?action=signin">Se connecter</a></li>
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