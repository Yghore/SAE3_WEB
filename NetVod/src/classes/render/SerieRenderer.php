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
                <li><a href="index.php?action=print-catalogue&id={$this->serie->id}">{$this->serie->titre} {$this->serie->img}</a>
            EOF;
            /*
            <div>
                    <li><a href="index.php?action=print-catalogue&id=$idserie">$titre $image</a>
            </div>*/
        }

        return $html;

    }
}