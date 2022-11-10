<?php

namespace iutnc\netvod\action;

use iutnc\netvod\model\Genre;
use iutnc\netvod\model\User;
use iutnc\netvod\model\User2genre;
use iutnc\netvod\render\GenresRenderer;
use iutnc\netvod\render\UserRenderer;

class Profil extends Action
{

    protected function executeGET(): string
    {
        //affichage du profil avec nom prenom age
        if(User::existSession()) {
            $user = User::getFromSession();
            $genreRenderer = new GenresRenderer(Genre::getGenres(), User2genre::getGenresByUser($user->id));
            $if = function (bool $condition, ?string $true, ?string $false) { return $condition ? $true : $false; };
            $html = <<<EOF
            <div class="">
                <div class="form">
                    <h1>Profil</h1>
                    {$if($user->isStricted(), '<p>Vous êtes mineur</p>', '<p>Vous êtes majeur</p>')}
                    <form action="index.php?action=profil" method="post">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" id="nom" value="{$if(isset($user->nom), $user->nom, "")}" required>
                        <label for="prenom">Prénom</label>
                        <input type="text" name="prenom" id="prenom" value="{$if(isset($user->prenom), $user->prenom, "")}" required>
                        <label for="date">Date de naissance</label>
                        <input type="date" name="date" id="date" value="{$if(isset($user->date_birth), $user->date_birth, "")}" required>
                        {$genreRenderer->render()}
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
            header('location: ?action=signin');
            die();

        }else if (isset($_POST['modifier'])) {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $date = $_POST['date'];
            $user = User::getFromSession();
            $user->nom = $nom;
            $user->prenom = $prenom;
            $user->date_birth = $date;
            $genres = [];
            foreach ($_POST as $key => $value) {
                if ($key != 'modifier' && $key != 'nom' && $key != 'prenom' && $key != 'deconnexion' && $key != 'date') {
                    $genres[$key] = $value;
                }
            }
            var_dump($genres);
            $user->genres = $genres;
            $user->save();
            $genresExisted = Genre::getGenres();
            foreach ($genresExisted as $genre) {
                $res = Genre::getGenreById($genre->id);
                if(isset($genres[$genre->id]))
                {
                    if (!User2genre::exists($user->id, $res->id)) {
                        User2genre::addGenre2user($user->id, $res->id);
                    }
                }
                else
                {
                    if(User2genre::exists($user->id, $res->id))
                    {
                        User2genre::deleteGenre2user($user->id, $res->id);
                    }
                }
            }
            header('location: ?action=profil');
            die();
        }
        return $res;
    }
}