<?php

namespace iutnc\netvod\dispatcher;

use iutnc\netvod\action\AddCommentNote;
use iutnc\netvod\action\AddFavorite;
use iutnc\netvod\action\AddUser;
use iutnc\netvod\action\CatalogueAction;
use iutnc\netvod\action\DeleteFavorite;
use iutnc\netvod\action\ForgotPassword;
use iutnc\netvod\action\Home;
use iutnc\netvod\action\Profil;
use iutnc\netvod\action\ResetPassword;
use iutnc\netvod\action\Signin;
use iutnc\netvod\action\Token;
use iutnc\netvod\model\User;

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
        switch ($action) {

            case 'add-user':
                $user = new AddUser();
                $html .= $user->execute();
                break;

            case 'signin':
                $signin = new Signin();
                $html .= $signin->execute();
                break;

            case 'print-catalogue':
                $catalogue = new CatalogueAction();
                $html .= $catalogue->execute();
                break;
            case 'add-favorite':
                $favorite = new AddFavorite();
                $html .= $favorite->execute();
                break;
            case 'delete-favorite':
                $favorite = new DeleteFavorite();
                $html .= $favorite->execute();
            case 'add-comment-note':
                $comment = new AddCommentNote();
                $html .= $comment->execute();
                break;

            case 'profil':
                $profil = new Profil();
                $html .= $profil->execute();
                break;
            case 'validate':
                $validate = new Token();
                $html .= $validate->execute();
                break;
            case 'forgotpassword':
                $forgotpassword = new ForgotPassword();
                $html .= $forgotpassword->execute();
                break;
            case 'resetpassword':
                $resetpassword = new ResetPassword();
                $html .= $resetpassword->execute();
                break;
            default:
                $home = new Home();
                $html .= $home->execute();
                break;
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html)
    {
        $render = <<<END
        <!DOCTYPE html>
        <html lang = "fr">
            <head>
                <title>NetVod - Video Streaming</title>
                <meta charset= "utf8" />
               <link rel="stylesheet" href="ressources/style.css" >
            </head>
            
            <body>
                <nav>
                    <div class="left">
                        <a id="img" href="?action=home"><img src="ressources/img/logo.png" alt="Logo NetVOD"></a>
                        <a href="?action=print-catalogue">Afficher le catalogue</a>
                        
                     </div>
                    <div class="right">
                       
        END;


        if (User::existSession()) {
            if($_GET['action']=='print-catalogue'){
                $render .= <<<END
                <form method="GET">
                    <label for="q">Rechercher une série : </label>
                    <input type="hidden" name="action" value="print-catalogue">
                    <input type="text" id="q" name="q">
                    <input type="submit" value="Rechercher">
                </form>
            </div>
            END;
            }
            $render .= <<<END
                <a href="?action=profil">Profil</a>
            END;

        } else {
            $render .= <<<END
                <a href="?action=add-user">Inscription</a>
                <a href="?action=signin">Se connecter</a>
            END;


        }
        $render .= <<<END
                </nav>
                <a href="javascript:history.back()" id="backButton" class="back"><img src="ressources/img/Button/backButton.png" alt="back"></a>                           
                 <div class="main">$html</div>
            </body>
        </html>
    END;
        echo $render;
    }
}