<?php

namespace iutnc\netvod\render;

use iutnc\netvod\model\Serie;

class SerieRenderer implements Renderer
{

    protected Serie $serie;

    public function __construct($s)
    {
        $this->serie = $s;
    }

    public function render(int $selector = 1): string
    {

        $html = <<<EOF
            <div>
                <p>Titre : {$this->serie->titre}</p>
                <p>Description : {$this->serie->descriptif}</p>
                <div>Img : <img src="{$this->serie->img}" alt="Image de la série"></div>
                <p>Année de sortie : {$this->serie->annee}</p>
                <p>Date ajout : {$this->serie->date_ajout}</p>
            </div>
        EOF;
        return $html;

    }
}