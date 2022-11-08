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

    private Renderer $renderer;


    public function run()
    {
        $this->renderer = new Renderer();
        $this->renderer->addHtmlWithViewData('header',[]);

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
                $this->renderer->addHtmlWithData($signin->execute());
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
                $this->renderer->addHtmlWithViewData('home', ['$titre' => 'Je suis un je suis une donnée']);
                $html .= $home->execute();
                break;
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html){

        Renderer::echo();
    }
}