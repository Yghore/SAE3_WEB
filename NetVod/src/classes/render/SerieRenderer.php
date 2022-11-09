<?php

namespace iutnc\netvod\render;

use iutnc\netvod\model\list\Serie;
use iutnc\netvod\model\User;

class SerieRenderer implements Renderer
{

    protected Serie $serie;

    public function __construct($s)
    {
        $this->serie = $s;
    }

    public function render(int $selector = 1): string
    {
        $html = '';
        if ($selector == 1){
            $html .= <<<EOF
            <div>
                <p>Titre : {$this->serie->titre}</p>
                <p>Description : {$this->serie->descriptif}</p>
                <p>Année de sortie : {$this->serie->annee}</p>
                <p>Date ajout : {$this->serie->date_ajout}</p>
                <p>Nombre d'episode dans la serie : {$this->serie->nbEpisodes}</p>
            </div>
        EOF;
        }
        if ($selector == 2) {
            $if = function (bool $condition, ?string $true, ?string $false) { return $condition ? $true : $false; };
            $addfavorite = <<<EOF

                    <form method="POST" action="?action=add-favorite">
                        <input type="hidden" name="url" value="/projet/NetVod/index.php?action=print-catalogue&amp;id=3">
                        <input type="hidden" name="idserie" value="{$this->serie->id}">
                        <input type="submit" value="Ajouter au favoris">
                     </form>
            EOF;
            $existfavorite = '<input type="submit" value="En favoris 👌" disabled>';

            $html .= <<<EOF
                <div class="card">
                    <a href="?action=print-catalogue&id={$this->serie->id}">
                    <div class="img" style='background: no-repeat url("ressources/img/{$this->serie->img}") center; background-size: cover'>

                    </div>
                    </a>
                     <div class="other">

                        {$if(User::getFromSession()->isFavoriteSerie($this->serie->id), $existfavorite, $addfavorite)}
                        <h4>{$this->serie->titre}</h4>
                        <p>{$this->serie->descriptif}</p>
                     </div>
                </div>
                
               
                
            EOF;
        }

        if ($selector == 3) {
            $id = $this->serie->id;
            $note = Serie::MoyenneNoteSerie($id);
            $html .= <<<EOF
                        <li><a href="index.php?action=print-catalogue&id={$this->serie->id}">{$this->serie->titre} {$this->serie->img}</a> 
                        <p>La note moyenne de cette série est de $note</p>
                        </li>
                     EOF;
        }

        return $html;

    }
}