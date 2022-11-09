<?php

namespace iutnc\netvod\action;

use iutnc\netvod\model\User;
use iutnc\netvod\render\UserRenderer;

class Profil extends Action
{

    protected function executeGET(): string
    {
        //affichage du profil avec nom prenom age
        $user = User::getFromSession();
        $ur = new UserRenderer($user);
        if(User::existSession()) {
            $html = <<<EOF
        <div class="bg-form">
            <div class="form">
                <h1>Profil</h1>
                <form action="index.php?action=profil" method="post">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" value="{$user->nom}" required>
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="{$user->prenom}" required>
                    {$ur->renderCheckBox()}
                    <input type="submit" value="Modifier">
                </form>
            </div>
            <div class="form">
                <h1>Déconnexion</h1>
                <form action="index.php?action=profil" method="post">
                    <input type="submit" value="Déconnexion" name="deconnexion">
                </form>
            </div>
        </div>

        EOF;

            return $html;

        }
        header('location: ?action=signin');
        die();

    }

    protected function executePOST(): string
    {
        //si le bouton deconnexion est appuyé alors on se deconnecte
        $res='';
        if (isset($_POST['deconnexion'])) {
            User::disconnect();
            $res =<<<EOF
            <h1>Vous êtes déconnecté</h1>
            EOF;

        }else if (isset($_POST['modifier'])) {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $age = $_POST['age'];
            $user = User::getFromSession();
            $user->nom = $nom;
            $user->prenom = $prenom;
            $user->date_birdth = $age;
            $res =<<<EOF
            <h1>Profil modifié</h1>
            EOF;
        }

        return $res;
    }
}