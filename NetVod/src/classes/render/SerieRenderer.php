<?php

namespace iutnc\netvod\render;

use iutnc\netvod\model\list\Serie;
use iutnc\netvod\model\User;

class SerieRenderer implements Renderer
{

    protected Serie $serie;
    protected string $url;

    public function __construct(Serie $s, string $url)
    {
        $this->serie = $s;
        $this->url = $url;
    }

    public function render(int $selector = 1): string
    {
        $if = function (bool $condition, ?string $true, ?string $false) { return $condition ? $true : $false; };
        $addfavorite = <<<EOF
                    <form method="POST" action="?action=add-favorite">
                        <input type="hidden" name="idserie" value="{$this->serie->id}">
                        <input type="hidden" name="url" value="{$this->url}">
                        <input type="submit" value="Ajouter aux favoris">
                     </form>
            EOF;
        $existfavorite = <<<EOF
                    <form method="POST" action="?action=delete-favorite">
                        <input type="hidden" name="idserie" value="{$this->serie->id}">
                        <input type="hidden" name="url" value="{$this->url}">
                        <input type="submit" value="Supprimer des favoris">
                     </form>
            EOF;
        $html = '';
        if ($selector == 1){
            $html .= $this->long($if, $existfavorite, $addfavorite);
        }
        if ($selector == 2) {
            $html .= $this->compact($if, $existfavorite, $addfavorite);
        }

        if ($selector == 3) {
            $html .= $this->noteMoyenneSerie();
        }

        return $html;

    }

    public function long ($if ,$existfavorite, $addfavorite){
        return <<<EOF
            <div>
                <p>Titre : {$this->serie->titre}</p>
                <p>Description : {$this->serie->descriptif}</p>
                <p>Année de sortie : {$this->serie->annee}</p>
                <p>Date ajout : {$this->serie->date_ajout}</p>
                <p>Nombre d'episode dans la serie : {$this->serie->nbEpisodes}</p>
                <div>{$if(User::getFromSession()->isFavoriteSerie($this->serie->id), $existfavorite, $addfavorite)}</div>
             
                 <div>
                    <a href="?action=add-comment-note&id={$this->serie->id}" class="btn">Ajouter un commentaire/note</a>
                 </div>
            </div>
        EOF;
    }

    public function compact ($if ,$existfavorite, $addfavorite) : string {
        $html = <<<EOF
                <div class="card">
                    <a href="?action=print-catalogue&id={$this->serie->id}
                EOF;
                if (User::existSession()){     
                    if(User::getFromSession()->isCurrentSerie($this->serie->id)) {
                        $idEp = User::getFromSession()->getCurrentEpisode($this->serie->id); 
                        $html.="&idepisode=$idEp";}
                }
                $html.=<<<EOF
                    ">
                    <div class="img" style='background: no-repeat url("{$this->serie->img}") center; background-size: cover'>

                    </div>
                    </a>
                     <div class="other">
                        
                        {$if(User::getFromSession()->isFavoriteSerie($this->serie->id), $existfavorite, $addfavorite)}
                        <h4>{$this->serie->titre}</h4>
                        <p>{$this->serie->descriptif}</p>
                     </div>
                </div>
                EOF;
        return $html;
    }

    public function noteMoyenneSerie() : string {
        $id = $this->serie->id;
        $note = Serie::MoyenneNoteSerie($id);
        return <<<EOF
                        <li><a href="index.php?action=print-catalogue&id={$this->serie->id}">{$this->serie->titre} {$this->serie->img}</a> 
                        <p>La note moyenne de cette série est de $note</p>
                        </li>
                     EOF;
    }


}