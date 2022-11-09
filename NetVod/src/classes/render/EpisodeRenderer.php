<?php

namespace iutnc\netvod\render;

use iutnc\netvod\model\video\Episode;

class EpisodeRenderer implements Renderer
{

    protected Episode $episode;

    public function __construct($episode){
        $this->episode = $episode;
    }

    public function render(int $selector = 1): string
    {
        $html = '';
        if ($selector == 1){
            $html = "<li><a href=\"index.php?action=print-catalogue&id= {$this->episode->serie_id} idserie&idepisode={$this->episode->id}\"> {$this->episode->numero} {$this->episode->titre} {$this->episode->duree} </a></li>";
        }

        if ($selector == 2){
            $html .= "</br>{$this->episode->titre}";
            $html .= "</br>{$this->episode->resume}";
            $html .= "</br>{$this->episode->duree}";
        }
        return $html;
    }
}