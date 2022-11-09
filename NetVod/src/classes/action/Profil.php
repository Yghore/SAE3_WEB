<?php

namespace iutnc\netvod\action;

use iutnc\netvod\model\User;
use iutnc\netvod\render\UserRenderer;

class Profil extends Action
{

    protected function executeGET(): string
    {
        //affichage du profil avec nom prenom age

        if(User::existSession()) {
            $user = User::getFromSession();
            $ur = new UserRenderer($user);
            $if = function (bool $condition, ?string $true, ?string $false) { return $condition ? $true : $false; };
            $html = <<<EOF
        <div class="">
            <div class="form">
                <h1>Profil</h1>
                <form action="index.php?action=profil" method="post">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" value="{$if(isset($user->nom), $user->nom, "")}" required>
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="{$if(isset($user->prenom), $user->prenom, "")}" required>
                    {$ur->renderCheckBox()}
                    <input type="submit" value="Modifier" name="modifier">
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
            $age = "";
            $user = User::getFromSession();
            $user->nom = $nom;
            $user->prenom = $prenom;
            $user->date_birth = $age;
            $user->save();
            header('location: ?action=profil');
            die();
        }

        return $res;
    }
}