<?php

namespace iutnc\netvod\render;

use iutnc\netvod\model\list\Serie;

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
                <div>Img : <img src="{$this->serie->img}" alt="Image de la série"></div>
                <p>Année de sortie : {$this->serie->annee}</p>
                <p>Date ajout : {$this->serie->date_ajout}</p>
                <p>Nombre d'episode dans la serie : {$this->serie->nbEpisodes}</p>
            </div>
        EOF;
        }
        if ($selector == 2) {
            $html .= <<<EOF
                <li><a href="index.php?action=print-catalogue&id={$this->serie->id}">{$this->serie->titre} {$this->serie->img}</a> <a href="?action=add-comment-note&id={$this->serie->id}"><button>Ajouter AVIS</button></a>
                <p>Il n'y a pas encore de note pour cette série</p>
                </li>
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