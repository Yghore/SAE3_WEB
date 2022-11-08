<?php

namespace iutnc\netvod\dispatcher;

use iutnc\netvod\action\AddEpisodeAction;
use iutnc\netvod\action\AddFavorite;
use iutnc\netvod\action\AddSerial;
use iutnc\netvod\action\AddUser;
use iutnc\netvod\action\CatalogueAction;
use iutnc\netvod\action\Home;
use iutnc\netvod\action\Signin;
use iutnc\netvod\render\Renderer;

class Dispatcher
{


    public function run()
    {
        $renderer = new Renderer();
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
                $addserial = new AddSerial();
                $html .= $addserial->execute();
                break;

            case 'print-catalogue':
                $catalogue = new CatalogueAction();
                $html .= $catalogue->execute();
                break;
            case 'add-favorite':
                $favorite = new AddFavorite();
                $html .= $favorite->execute();
            case 'add-episode':
                $episode = new AddEpisodeAction();
                $html .= $episode->execute();
                break;

            default:
                $home = new Home();
                $renderer->addHtmlWithData('home', ['$titre' => 'Je suis un je suis une donnée']);
                $html .= $home->execute();
                break;
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html){
        Renderer::echo();
        /**echo <<<END
        <!DOCTYPE html>
        <html lang = "fr">
            <head>
                <title>NetVod - Video Streaming</title>
                <meta charset= "utf8" />
            </head>
            
            <body>
                <h1>NetVod - Video Streaming</h1>
                <nav><ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="?action=add-user">Inscription</a></li>
                    <li><a href="?action=signin">Se connecter</a></li>
                    <li><a href="?action=add-serial">ajouter une serie</a></li>
                    <li><a href="?action=print-catalogue">afficher la categorie</a></li>
                    <li><a href="?action=add-episode">ajouter un episode</a></li>
                </nav></ul>
                <div>$html</div>
            </body>
        </html>
    END;**/
    }
}